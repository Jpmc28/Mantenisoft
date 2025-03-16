<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipo_usuario'] != 'activos' && $_SESSION['tipo_usuario'] != 'super_usuario')) {
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
        <div id="pregunta"><h1>¿Que tipo de activo quieres dar de baja?</h1><div id="menu"><a href="../../inicioactivos.php"><img src="img/home-removebg-preview.png" alt="devolver"></a></div></div>
        <div id="seleccion">
        <a href="bajai.php"><img src="img/impresorasin.png" alt="impresoras" class="img"></a>
        <a href="bajat.php"><img src="img/telefonosin.png" alt="telefonos" class="img"></a>
        <a href="baja.php"><img src="img/computadorsin.png" alt="computadores" class="img"></a>
        </div>
    </div>
</body>
</html>