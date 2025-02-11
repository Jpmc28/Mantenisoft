<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'activos') {
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
            <section id="servicios"><p>Activos Insertados</p></section>
            <section id="serviciosRealizados">
            </section>
        </section>
    </section>
    <div class="menu-container">
    <div class="menu-icon">
      <img src="img/imagen_principal-removebg-preview.png" alt="Menú">
    </div>
    <div class="dropdown-menu">
      <a href="menus/telefono.php"><img src="img/telefono-removebg-preview.png" alt="Inserte equipo telefonico" title="Inserte equipo telefonico" id="foto1"></a>
      <a href="menus/printer.php"><img src="img/impresora-removebg-preview.png" alt="Inserte equipo de impresion" title="Inserte equipo de impresion" id="foto2"></a>
      <a href="menus/computers.php"><img src="img/computadores-removebg-preview.png" alt="Inserte equipo de computo" title="Inserte equipo de computo" id="foto3"></a>
    </div>
  </div>
</body>
</html>