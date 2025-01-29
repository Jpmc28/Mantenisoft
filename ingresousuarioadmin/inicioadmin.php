<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['IdCedula'])) {
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
            <h3>Nombre: <?php echo htmlspecialchars($_SESSION['Nombre']); ?></h3>
            <h3>CC:  <?php echo htmlspecialchars($_SESSION['IdCedula']); ?></h3>
            <h3>Cargo: <?php echo htmlspecialchars($_SESSION['Rol']); ?></h3>
            <h3>Correo:  <?php echo htmlspecialchars($_SESSION['InstitucionalEmail']); ?></h3>
            <button id="boton_actualizar"><a href="actualizardatos.php">Actualizar</a></button>
        </section>
        <section id="tablaServicios">
            <section id="servicios"><p>Servicios Realizados</p></section>
            <section id="serviciosRealizados">
            </section>
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
      <a href="menus/timbre.php"><img src="img/telefono1-removebg-preview.png" alt="Servicio 4" title="Servicio 4" id="foto4"></a>
    </div>
  </div>
</body>
</html>