<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['IdCedula'])) {
    header("Location: index.php");
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
    <section id="tablaPeronal">
        <img src="img/informacionbasica.png" alt="">
        <h3>Nombre: <?php echo htmlspecialchars($_SESSION['Name']); ?></h3>
        <h3>CC:  <?php echo htmlspecialchars($_SESSION['IdCedula']); ?></h3>
        <h3>Cargo: <?php echo htmlspecialchars($_SESSION['Rol']); ?></h3>
        <h3>Correo:  <?php echo htmlspecialchars($_SESSION['InstitucionalEmail']); ?></h3>
        <button id="boton_actualizar"><a href="#">Actualizar</a></button>
    </section>
    <section id="tablaServicios">
        <section id="servicios"><H2>Servicios Realizados</H2></section>
        <section id="serviciosRealizados">
            
        </section>
    </section>
    <button><a href=""></a></button>
</body>
</html>