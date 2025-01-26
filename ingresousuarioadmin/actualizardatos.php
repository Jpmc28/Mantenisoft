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
    <link rel="stylesheet" href="css/editar_datos.css">
    <link rel="website icon" href="img/GtuzsKu2ryrS5m0Z-removebg-preview1.png">
    <title>mantenisoft</title>
</head>
<body>
    <div id="seccion_actualizar_datos">
        <div id="logoyactualizar">
        <div id="logo">
        <img src="img/logo_clinica-removebg-preview.png" alt="logoCA">
        </div>
        <form action="../phpbacklogeoandresgister/actualizardatos.php" method="POST" class="form-container">
        <input class="registrar" name="nombre" type="text"  placeholder="Nombre" required>
        <input class="registrar" name="numero" type="text"  placeholder="Teléfono" required>
        <input class="registrar" name="Cedula" type="text"  placeholder="Cedula" required>
        <input type="email" name="email" class="registrar" placeholder="Correo" required>
        </form>
        </div>
        <div id="imgfuera">
            <img src="img/logosof-removebg-preview.png" alt="">
        </div>
    </div>
    <div id="botones">
    <button type="submit" id="guardar"><h3>Guardar</h3></button>
    <a href="inicioadmin.php">
    <button id="cancelar"><h3>cancelar</h3></button>
    </a>
    </div>
</body>
</html>