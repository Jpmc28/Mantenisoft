<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipo_usuario'] != 'activos' && $_SESSION['tipo_usuario'] != 'super_usuario')) {
  header("Location: ../../../../index.php");
  exit();
}
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
        "S2" => "1002",
        "dies" => "1100",
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
        WHERE p.id_piso = ? and a.tipo = 'telefono';";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("<p style='color: red;'>Error en la preparación de la consulta: " . $conn->error . "</p>");
}

$stmt->bind_param("s", $piso_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    echo "<div class='contenedor'>";
    echo "<h1>¿Qué equipo quieres ver?</h1>";
    echo "<div class='activos'>"; // Contenedor con Grid
    
    while ($fila = $resultado->fetch_assoc()) {
        echo "<a href='eliminar_activo/elimarcomputador.php?id_activo=" . $fila['id_activo'] . "'><div class='activo'>" . $fila['NPlaca'] . "</div></a>";
    }
    
    echo "</div>"; // Cierre de .activos
    echo "</div>"; // Cierre de .contenedor    

} else {
    echo "<div class='contenedor'>";
    echo "<h1>¿Qué equipo quieres ver?</h1>";
    echo "<div class='activos'>";
        echo "<div class='activo'style='color: red;'>No hay activos registrados en este piso.</div>";
    echo "</div>";
    echo "</div>";
}

// Cerrar conexión
$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/visualizacionp1.css">
    <title>mantenisoft</title>
</head>
<body>
</body>
</html>