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
        <h1>¿Que activo observaremos?</h1>
        <a href="bajai.php"><img src="img/impresorasin.png" alt="impresoras"></a>
        <a href="bajat.php"><img src="img/telefonosin.png" alt="telefonos"></a>
        <a href="baja.php"><img src="img/computadorsin.png" alt="computadores"></a>
    </div>
</body>
</html>