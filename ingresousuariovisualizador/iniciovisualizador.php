<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipo_usuario'] != 'visualizador' && $_SESSION['tipo_usuario'] != 'super_usuario')) {
  header("Location: ../index.php");
  exit();
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
            <section id="servicios">Información</section>
            <h1>Su usuario será el encargado de obtener reportes sobre:</h1>
            <ul>
                <li><i class="fas fa-phone-slash"></i> <h2>Daños a teléfonos como del cable de la fuente de alimentacion, cable de voz y bocina</h2></li>
                <li><i class="fas fa-tools"></i> <h2>Mantenimientos de equipos de cómputo donde vera un reporte detallado de como fueron los mantenimientos y las areas por piso donde se han realizado todos los mantenimientos</h2></li>
                <li><i class="fas fa-print"></i> <h2>Suministros de impresora consumidos como toner y drum de esta manera mostrando las areas por piso que consumen mas insumos</h2></li>
            </ul>
        </section>
    </section>
    <div class="menu-container">
    <div class="menu-icon">
      <img src="img/imagen_principal-removebg-preview.png" alt="Menú">
    </div>
    <div class="dropdown-menu">
      <a href="menu/telefono.php"><img src="img/telefono-removebg-preview.png" alt="Servicio 1" title="Servicio 1" id="foto1"></a>
      <a href="menu/printer.php"><img src="img/impresora-removebg-preview.png" alt="Servicio 2" title="Servicio 2" id="foto2"></a>
      <a href="menu/computers.php"><img src="img/computadores-removebg-preview.png" alt="Servicio 3" title="Servicio 3" id="foto3"></a>
    </div>
  </div>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>