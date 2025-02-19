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
    <div>
        <a href="consulta/impresoras.php"><img src="" alt="Impresora"></a>
        <a href="consulta/telefonos.php"><img src="" alt="Telefonos"></a>
        <a href="consulta/computers.php"><img src="" alt="Computadores"></a>
    </div>
</body>
</html>
