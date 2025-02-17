<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'activos') {
    header("Location: ../../../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/baja1.css">
    <link rel="website icon" href="img/GtuzsKu2ryrS5m0Z-removebg-preview1.png">
    <title>Mantenisoft</title>
</head>
<body>
    <div id="tipo">
        <div id="impresoras"><h2>impresoras</h2><img src="img/" alt=""></div>
        <div id="telefonos"><h2>telefonos</h2><img src="img/" alt=""></div>
        <div id="computadores"><h2>computadores</h2><img src="img/" alt=""></div>
    </div>
</body>
</html>