<?php
// Conectar a la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mantenisoft';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("<p style='color: red;'>Error de conexión: " . $conn->connect_error . "</p>");
}

// Definir fechas predeterminadas si no se envían por GET
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');

// Verificar si la base de datos contiene los datos correctos antes de ejecutar las consultas

// Consultar cantidad de dispositivos registrados por fecha
$sql_dispositivos = "SELECT DATE(fecha_ingreso) as fecha, COUNT(*) as cantidad 
                     FROM activos 
                     WHERE fecha_ingreso BETWEEN ? AND ? 
                     GROUP BY fecha ORDER BY fecha DESC";

$stmt = $conn->prepare($sql_dispositivos);
if ($stmt) {
    $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
    $stmt->execute();
    $result = $stmt->get_result();
    $dispositivos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    die("Error en la consulta de dispositivos: " . $conn->error);
}

// Consultar áreas con más mantenimientos
$sql_areas = "SELECT ar.nombre_area, COUNT(m.id_mantenimiento) as total_mantenimientos 
              FROM mantenimientos m 
              JOIN activos a ON m.id_activo = a.id_activo
              JOIN areas ar ON a.id_area = ar.id_area
              WHERE m.fecha_mantenimiento BETWEEN ? AND ?
              GROUP BY ar.nombre_area 
              ORDER BY total_mantenimientos DESC 
              LIMIT 5";

$stmt = $conn->prepare($sql_areas);
if ($stmt) {
    $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
    $stmt->execute();
    $result = $stmt->get_result();
    $areas = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    die("Error en la consulta de áreas: " . $conn->error);
}

// Consultar historial de mantenimientos por dispositivo
$sql_historial = "SELECT m.id_activo, a.nombre, m.descripcion, DATE(m.fecha_mantenimiento) as fecha 
                  FROM mantenimientos m 
                  JOIN activos a ON m.id_activo = a.id_activo 
                  WHERE m.fecha_mantenimiento BETWEEN ? AND ?
                  ORDER BY m.fecha_mantenimiento DESC";

$stmt = $conn->prepare($sql_historial);
if ($stmt) {
    $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
    $stmt->execute();
    $result = $stmt->get_result();
    $historial = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    die("Error en la consulta del historial: " . $conn->error);
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Dispositivos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Reporte de Dispositivos</h1>
    <form method="GET">
        <label>Desde: <input type="date" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>"></label>
        <label>Hasta: <input type="date" name="fecha_fin" value="<?php echo $fecha_fin; ?>"></label>
        <button type="submit">Consultar</button>
    </form>

    <h2>Dispositivos Registrados</h2>
    <table border="1">
        <tr><th>Fecha</th><th>Cantidad</th></tr>
        <?php foreach ($dispositivos as $d) { ?>
            <tr><td><?php echo htmlspecialchars($d['fecha']); ?></td><td><?php echo htmlspecialchars($d['cantidad']); ?></td></tr>
        <?php } ?>
    </table>

    <h2>Áreas con más Mantenimientos</h2>
    <table border="1">
        <tr><th>Área</th><th>Total Mantenimientos</th></tr>
        <?php foreach ($areas as $a) { ?>
            <tr><td><?php echo htmlspecialchars($a['nombre_area']); ?></td><td><?php echo htmlspecialchars($a['total_mantenimientos']); ?></td></tr>
        <?php } ?>
    </table>

    <h2>Historial de Mantenimientos</h2>
    <table border="1">
        <tr><th>ID Equipo</th><th>Nombre</th><th>Descripción</th><th>Fecha</th></tr>
        <?php foreach ($historial as $h) { ?>
            <tr>
                <td><?php echo htmlspecialchars($h['id_activo']); ?></td>
                <td><?php echo htmlspecialchars($h['nombre']); ?></td>
                <td><?php echo htmlspecialchars($h['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($h['fecha']); ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
