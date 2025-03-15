<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'activos') {
    header("Location: ../../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/consultarActivos.css">
    <link rel="website icon" href="img/GtuzsKu2ryrS5m0Z-removebg-preview1.png">
    <title>mantenisoft</title>
</head>
<body>
    <div id="todo">
        <div id="texto"><h1>Escoge el dispositivo que quieres observar</h1></div>
        <a href="consulta/computers.php"><img src="img/computadores-removebg-preview.png" alt="Computadores" class="img"></a>
        <div id="menu"><a href="../inicioactivos.php"><img src="img/home-removebg-preview.png" alt="devolver"></a></div>
    </div>
</body>
</html>
