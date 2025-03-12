<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: ../index.php");
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

if (!empty($_GET['id_activo'])) {
  $_SESSION['id_activo'] = intval($_GET['id_activo']);
}

$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener los mantenimientos del equipo
$sql_mantenimientos = "SELECT m.id_mantenimiento, m.fecha_mantenimiento, u.nombre AS nombre_usuario, m.estado, esp.nombre_dominio
                        FROM mantenimientos m
                        JOIN usuarios u ON m.id_usuario = u.id_usuario
                        JOIN especificaciones esp ON m.id_activo = esp.id_activo
                        WHERE m.id_usuario = ?
                        ORDER BY m.fecha_mantenimiento DESC;";


$stmt_mantenimientos = $conn->prepare($sql_mantenimientos);
$stmt_mantenimientos->bind_param("i", $id_usuario);
$stmt_mantenimientos->execute();
$resultado_mantenimientos = $stmt_mantenimientos->get_result();

// Guardar los mantenimientos en un array
$mantenimientos = [];
while ($fila = $resultado_mantenimientos->fetch_assoc()) {
    $mantenimientos[] = $fila;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estiloInicio.css">
    <link rel="website icon" href="img/GtuzsKu2ryrS5m0Z-removebg-preview1.png">
    <title>InicioAdmin</title>
</head>
<body>
  <img src="img/GtuzsKu2ryrS5m0Z-removebg-preview.png" alt="" id="logocli">
    <section id="personalydatots">
        <section id="tablaPeronal">
            <img src="img/informacionbasica.png" alt="" id="cabeza">
            <h3>Nombre: <?php echo htmlspecialchars($_SESSION['nombre']); ?></h3>
            <h3>CC:  <?php echo htmlspecialchars($_SESSION['Cedula']); ?></h3>
            <h3>Cargo: <?php echo htmlspecialchars($_SESSION['Rol']); ?></h3>
            <h3>Correo:  <?php echo htmlspecialchars($_SESSION['correo']); ?></h3>
            <a href="actualizardatos.php"><button id="boton_actualizar">Actualizar</button></a>
        </section>
        <section id="tablaServicios">
            <section id="servicios"><p>Servicios Realizados</p></section>
            <div class="historial-mantenimiento">
                <?php if (!empty($mantenimientos)): ?>
                    <ul>
                        <?php foreach ($mantenimientos as $mantenimiento): ?>
                            <li>
                                <span class="fecha"><?php echo htmlspecialchars($mantenimiento['fecha_mantenimiento']); ?></span>
                                <span class="usuario"><?php echo htmlspecialchars($mantenimiento['nombre_dominio']); ?></span>
                                <span class="estado">(<?php echo htmlspecialchars($mantenimiento['estado']); ?>)</span>
                                
                                <?php if ($mantenimiento['estado'] === 'En Proceso'): ?>
                                    <form action="retomar_mantenimiento.php" method="GET" class="retomar-form">
                                        <input type="hidden" name="id_mantenimiento" value="<?php echo $mantenimiento['id_mantenimiento']; ?>">
                                        <button type="submit" class="retomar-btn">Retomar</button>
                                    </form>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No hay mantenimientos registrados para este equipo.</p>
                <?php endif; ?>
            </div>
        </section>
    </section>
    <div class="menu-container">
    <div class="menu-icon">
      <img src="img/imagen_principal-removebg-preview.png" alt="Menú">
    </div>
    <div class="dropdown-menu">
      <a href="menus/telefono.php"><img src="img/telefono-removebg-preview.png" alt="Servicio 1" title="Servicio 1" id="foto1"></a>
      <a href="menus/printer.php"><img src="img/impresora-removebg-preview.png" alt="Servicio 2" title="Servicio 2" id="foto2"></a>
      <a href="menus/computers.php"><img src="img/computadores-removebg-preview.png" alt="Servicio 3" title="Servicio 3" id="foto3"></a>
    </div>
  </div>
</body>
</html>