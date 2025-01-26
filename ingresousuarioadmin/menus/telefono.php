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
    <link rel="stylesheet" href="css/estilostelefonos.css">
    <link rel="website icon" href="img/GtuzsKu2ryrS5m0Z-removebg-preview1.png">
    <title>mantenisoft</title>
</head>
<body>
    <div id="elementosdesglozados">
        <div id="texto"><h1>Elige el piso donde deseas buscar información</h1></div>
        <div id="separador">
            <div>
                <div class="piso" data-floor="S2"><a href=""><h1>S2</h1></a></div>
                <div class="piso" data-floor="3"><a href=""><h1>Piso 3</h1></a></div>
                <div class="piso" data-floor="7"><a href=""><h1>Piso 7</h1></a></div>
            </div>
            <div>
                <div class="piso" data-floor="S1"><a href=""><h1>S1</h1></a></div>
                <div class="piso" data-floor="4"><a href=""><h1>Piso 4</h1></a></div>
                <div class="piso" data-floor="8"><a href=""><h1>Piso 8</h1></a></div>
            </div>
            <div>
                <div class="piso" data-floor="1"><a href=""><h1>Piso 1</h1></a></div>
                <div class="piso" data-floor="5"><a href=""><h1>Piso 5</h1></a></div>
                <div class="piso" data-floor="9"><a href=""><h1>Piso 9</h1></a></div>
            </div>
            <div>
                <div class="piso" data-floor="2"><a href=""><h1>Piso 2</h1></a></div>
                <div class="piso" data-floor="6"><a href=""><h1>Piso 6</h1></a></div>
                <div class="piso" data-floor="10"><a href=""><h1>Piso 10</h1></a</div>
            </div>
        </div>
    </div>
    <a href="../inicioadmin.php"><img src="img/home-removebg-preview.png" alt=""></a>
</body>
</html>