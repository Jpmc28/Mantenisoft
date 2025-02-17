<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilostelefonos.css">
    <link rel="website icon" href="img/GtuzsKu2ryrS5m0Z-removebg-preview1.png">
    <title>Mantenisoft</title>
</head>
<body>
    <img src="img/GtuzsKu2ryrS5m0Z-removebg-preview.png" alt="" id="logocli">  
    <div id="elementosdesglozados">
        <div id="texto"><h1>Elige el piso donde deseas buscar información</h1></div>
        <div id="separador">
            <div>
                <a href="computers/visualizacionpiso1.php?piso=S2"><div class="piso"><h1>S2</h1></div></a>
                <a href="computers/visualizacionpiso1.php?piso=30"><div class="piso"><h1>Piso 3</h1></div></a>
                <a href="computers/visualizacionpiso1.php?piso=7"><div class="piso"><h1>Piso 7</h1></div></a>
            </div>
            <div>
                <a href="computers/visualizacionpiso1.php?piso=S1"><div class="piso"><h1>S1</h1></div></a>
                <a href="computers/visualizacionpiso1.php?piso=40"><div class="piso"><h1>Piso 4</h1></div></a>
                <a href="computers/visualizacionpiso1.php?piso=80"><div class="piso"><h1>Piso 8</h1></div></a>
            </div>
            <div>
                <a href="computers/visualizacionpiso1.php?piso=10"><div class="piso" name="1"><h1>Piso 1</h1></div></a>
                <a href="computers/visualizacionpiso1.php?piso=50"><div class="piso"><h1>Piso 5</h1></div></a>
                <a href="computers/visualizacionpiso1.php?piso=90"><div class="piso"><h1>Piso 9</h1></div></a>
            </div>
            <div>
                <a href="computers/visualizacionpiso1.php?piso=20"><div class="piso"><h1>Piso 2</h1></div></a>
                <a href="computers/visualizacionpiso1.php?piso=60"><div class="piso"><h1>Piso 6</h1></div></a>
                <a href="computers/visualizacionpiso1.php?piso=dies"><div class="piso"><h1>Piso 10</h1></div></a>
            </div>
        </div>
        <a href="../inicioadmin.php"><img src="img/home-removebg-preview.png" alt="" id="imgicon"></a>
    </div>
</body>
</html>