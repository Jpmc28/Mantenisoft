<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/visualizacionp1.css">
    <link rel="website icon" href="img/GtuzsKu2ryrS5m0Z-removebg-preview1.png">
    <title>mantenisoft</title>
</head>
<body>
    <div id="hoja_de_vida">
        <div id="fotoyperifericos">
    <div id="foto_equipo"></div>
    <div class="perifericos"></div>
    <div class="perifericos"></div>
    <div class="perifericos"></div>
        </div>
        <div id="detalles">
    <div class="detalles"></div>
    <div class="detalles"></div>
    <div class="detalles"></div>
    <div class="detalles"></div>
    <div class="detalles"></div>
    <div class="detalles"></div>
        </div>
    </div>
</body>
</html>