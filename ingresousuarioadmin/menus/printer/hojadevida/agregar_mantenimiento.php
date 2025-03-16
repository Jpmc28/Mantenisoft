<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: ../../../../index.php");
    exit();
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mantenisoft';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_activo = $_GET['id_activo'] ?? null;
    $tipo_cambio = $_POST['tipo_cambio'] ?? null;
    $id_usuario = $_SESSION['id_usuario'];

    if (empty($id_activo) || empty($tipo_cambio)) {
        die("Error: Datos incompletos.");
    }

    if (!in_array($tipo_cambio, ['tóner', 'drum'])) {
        die("Error: Tipo de cambio inválido.");
    }

    $sql = "INSERT INTO cambios_impresoras (id_activo, tipo_cambio, id_usuario) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("isi", $id_activo, $tipo_cambio, $id_usuario);

    if ($stmt->execute()) {
        header("Location: hojadevida.php?id_activo=" . $id_activo . "&success=1");
        exit();
    } else {
        die("Error al registrar el cambio: " . $stmt->error);
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Cambio de Suministro</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Cambio de Suministro</h2>
        <form method="POST">
            <label for="tipo_cambio">Seleccione el suministro:</label>
            <select id="tipo_cambio" name="tipo_cambio" required>
                <option value="tóner">Tóner</option>
                <option value="drum">Drum</option>
            </select>
            <button type="submit" class="btn">Registrar Cambio</button>
        </form>
    </div>
</body>
</html>
