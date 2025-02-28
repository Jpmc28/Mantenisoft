<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'activos') {
    header("Location: ../../../../index.php");
    exit();
}

// Conectar a la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mantenisoft';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("<p style='color: red;'>Error de conexión: " . $conn->connect_error . "</p>");
}

// Obtener el ID del activo desde la URL
if (!isset($_GET['id_activo'])) {
    die("<p style='color: red;'>Error: No se ha especificado un equipo a eliminar.</p>");
}
$id_activo = intval($_GET['id_activo']);

// Consulta para obtener la información del equipo
$sql = "SELECT a.nombre, a.tipo, a.estado, a.NPlaca, ar.nombre_area, 
               es.procesador, es.ram, es.almacenamiento, es.sistema_operativo, es.software_instalado, es.nombre_dominio, a.imagen
        FROM activos a
        JOIN areas ar ON a.id_area = ar.id_area
        JOIN especificaciones es ON a.id_activo = es.id_activo
        WHERE a.id_activo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_activo);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si hay resultados
if ($resultado->num_rows == 0) {
    die("<p style='color: red;'>No se encontró información para el equipo seleccionado.</p>");
}

$equipo = $resultado->fetch_assoc();

if (!$equipo || empty($equipo['imagen'])) {
    die("imagen no encontrada.");
}

//mostrar la imagen
header("Content-Type: image/jpeg");
echo $equipo['imagen'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/eliminacioncomputador.css">
    <title>Eliminar Computador</title>
</head>
<body>

<div class="contenedor">
    <h1>Detalles del Computador</h1>
    <div class="tarjeta">
        <h2><?php echo htmlspecialchars($equipo['nombre']); ?></h2>
        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($equipo['tipo']); ?></p>
        <p><strong>Estado:</strong> <?php echo htmlspecialchars($equipo['estado']); ?></p>
        <p><strong>Número de Placa:</strong> <?php echo htmlspecialchars($equipo['NPlaca']); ?></p>
        <p><strong>Área:</strong> <?php echo htmlspecialchars($equipo['nombre_area']); ?></p>
        <h3>Especificaciones</h3>
        <p><strong>Procesador:</strong> <?php echo htmlspecialchars($equipo['procesador']); ?></p>
        <p><strong>RAM:</strong> <?php echo htmlspecialchars($equipo['ram']); ?></p>
        <p><strong>Almacenamiento:</strong> <?php echo htmlspecialchars($equipo['almacenamiento']); ?></p>
        <p><strong>Sistema Operativo:</strong> <?php echo htmlspecialchars($equipo['sistema_operativo']); ?></p>
        <p><strong>Software Instalado:</strong> <?php echo htmlspecialchars($equipo['software_instalado']); ?></p>
        <p><strong>Nombre de Dominio:</strong> <?php echo htmlspecialchars($equipo['nombre_dominio']); ?></p>
        <p><img src="elimarcomputador.php?id_activo=<?php echo $id_activo; ?>" alt="Imagen del equipo" class="imagen-equipo"></p>
        
        <form action="procesar_eliminacion.php" method="POST">
            <input type="hidden" name="id_activo" value="<?php echo $id_activo; ?>">
            <button type="submit" class="btn-eliminar">Eliminar Computador</button>
        </form>
    </div>
</div>

</body>
</html>