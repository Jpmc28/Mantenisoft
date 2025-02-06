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
    <link rel="stylesheet" href="css/visualizacionp235.css">
    <link rel="website icon" href="img/GtuzsKu2ryrS5m0Z-removebg-preview1.png">
    <title>mantenisoft</title>
</head>
<body>
    <form action="back/subir_imagen.php" method="post" enctype="multipart/form-data">
        <input type="file" name="imagen" accept="image/*" required>
        <input type="text"name="nombre">
        <input type="text" name="tipo">
        <input type="text" name="estado">
        <input type="text" name="area">
        <input type="text" name="id_usuario">
        <input type="text" name="NPlaca">
        <button type="submit">subir imagen</button>
    </form>
</body>
</html>