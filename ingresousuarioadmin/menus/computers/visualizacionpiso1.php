<?php

$host = 'localhost';
$user = 'root';      
$password = '';      
$database = 'mantenisoft';  

$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("<p style='color: red;'>Error de conexión: " . $conn->connect_error . "</p>");
}

// Verificar si el parámetro 'piso' está en la URL
if (!isset($_GET['piso']) || empty($_GET['piso'])) {
    die("<p style='color: red;'>Error: No se ha seleccionado un piso. <a href='../index.php'>Volver</a></p>");
}

$piso = $_GET['piso'];

// Generar el ID del piso basado en el valor recibido
if (is_numeric($piso)) {
    // Si el piso es un número, formatearlo correctamente
    $piso_id = "10" . str_pad($piso, 2, "0", STR_PAD_LEFT);
} else {
    // Si es un sótano (S1, S2), asignar manualmente los IDs correspondientes
    $sotanos = [
        "S1" => "1001",
        "S2" => "1002"
    ];

    $piso_id = $sotanos[$piso] ?? null;
}

if (!$piso_id) {
    die("<p style='color: red;'>Error: Piso no válido.</p>");
}

// Preparar la consulta
$sql = "SELECT a.id_activo, a.nombre, a.tipo, a.estado, a.NPlaca, ar.nombre_area
        FROM activos a
        JOIN areas ar ON a.id_area = ar.id_area
        JOIN pisos p ON ar.id_piso = p.id_piso
        WHERE p.id_piso = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("<p style='color: red;'>Error en la preparación de la consulta: " . $conn->error . "</p>");
}

$stmt->bind_param("s", $piso_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    echo "<h1>Activos en el piso: $piso</h1>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Estado</th><th>Placa</th><th>Área</th></tr>";

    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>
                <td>{$fila['id_activo']}</td>
                <td>{$fila['nombre']}</td>
                <td>{$fila['tipo']}</td>
                <td>{$fila['estado']}</td>
                <td>{$fila['NPlaca']}</td>
                <td>{$fila['nombre_area']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>No hay activos registrados en este piso.</p>";
}

// Cerrar conexión
$stmt->close();
$conn->close();

?>


