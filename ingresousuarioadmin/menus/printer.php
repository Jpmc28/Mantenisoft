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
    <title>mantenisoft</title>
</head>
<body>
    
</body>
</html>