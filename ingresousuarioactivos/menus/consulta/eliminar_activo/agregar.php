<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'activos') {
    header("Location: ../../../../index.php");
    exit();
}

// Conectar a la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mantenisoft';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("<p style='color: red;'>Error de conexión: " . $conn->connect_error . "</p>");
}
$id_activo = intval($_GET['id_activo']); // Sanitiza el ID para evitar inyecciones SQL

// Si el formulario se envía, procesar los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $perifericos = ['monitor', 'teclado', 'mouse']; // Lista de periféricos permitidos

    // Conectar a la base de datos
    $conn = new mysqli($host, $user, $password, $database);
    if ($conn->connect_error) {
        die("<p style='color: red;'>Error de conexión: " . $conn->connect_error . "</p>");
    }

    $errores = [];
    
    // Recorrer cada periférico y agregarlo si tiene número de placa
    foreach ($perifericos as $tipo) {
        if (!empty($_POST[$tipo])) {
            $placa = trim($_POST[$tipo]);

            // Insertar en la base de datos
            $sql = "INSERT INTO perifericos (tipo, placa, id_activo) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $tipo, $placa, $id_activo);
            
            if (!$stmt->execute()) {
                $errores[] = "Error al agregar el $tipo: " . $stmt->error;
            }

            $stmt->close();
        }
    }

    $conn->close();

    // Si no hubo errores, redirigir a otra página
    if (empty($errores)) {
        header("Location: elimarcomputador.php?mensaje=Periféricos agregados correctamente");
        exit();
    } else {
        foreach ($errores as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Periféricos</title>
    <link rel="stylesheet" href="css/agregar.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Agregar Periféricos al Activo #<?php echo $id_activo; ?></h2>
        <form action="" method="POST">
            <label for="monitor">Placa del Monitor:</label>
            <input type="text" name="monitor" id="monitor" placeholder="Ingrese la placa del monitor">

            <label for="teclado">Placa del Teclado:</label>
            <input type="text" name="teclado" id="teclado" placeholder="Ingrese la placa del teclado">

            <label for="mouse">Placa del Mouse:</label>
            <input type="text" name="mouse" id="mouse" placeholder="Ingrese la placa del mouse">

            <button type="submit">Guardar Periféricos</button>
        </form>
    </div>
</body>
</html>


