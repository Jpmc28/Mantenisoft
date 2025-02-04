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
    <link rel="stylesheet" href="css/estilostelefonos.css">
    <link rel="website icon" href="img/GtuzsKu2ryrS5m0Z-removebg-preview1.png">
    <title>mantenisoft</title>
</head>
<body>
    <img src="img/GtuzsKu2ryrS5m0Z-removebg-preview.png" alt="" id="logocli">
    <div id="elementosdesglozados">
        <div id="texto"><h1>Elige el piso donde deseas buscar información</h1></div>
        <div id="separador">
            <div>
                <a href="#"><div class="piso" data-floor="S2"><h1>S2</h1></div></a>
                <a href="telefono/visualizacionp3.php"><div class="piso" data-floor="3"><h1>Piso 3</h1></div></a>
                <a href="telefono/visualizacionp678.php"><div class="piso" data-floor="7"><h1>Piso 7</h1></div></a>
            </div>
            <div>
                <a href="telefono/visualizacions1.php"><div class="piso" data-floor="S1"><h1>S1</h1></div></a>
                <a href="telefono/visualizacionp4.php"><div class="piso" data-floor="4"><h1>Piso 4</h1></div></a>
                <a href="telefono/visualizacionp678.php"><div class="piso" data-floor="8"><h1>Piso 8</h1></div></a>
            </div>
            <div>
                <a href="telefono/visualizacionp1.php"><div class="piso" data-floor="1"><h1>Piso 1</h1></div></a>
                <a href="telefono/visualizacionp5.php"><div class="piso" data-floor="5"><h1>Piso 5</h1></div></a>
                <a href="telefono/visualizacionp9.php"><div class="piso" data-floor="9"><h1>Piso 9</h1></div></a>
            </div>
            <div>
                <a href="telefono/visualizacionp2.php"><div class="piso" data-floor="2"><h1>Piso 2</h1></div></a>
                <a href="telefono/visualizacionp678.php"><div class="piso" data-floor="6"><h1>Piso 6</h1></div></a>
                <a href="telefono/visualizacionp10.php"><div class="piso" data-floor="10"><h1>Piso 10</h1></div></a>
            </div>
        </div>
        <a href="../inicioadmin.php"><img src="img/home-removebg-preview.png" alt="" id="imgicon"></a>
    </div>
</body>
</html>