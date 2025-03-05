<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    die("Acceso no autorizado.");
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
    $id_activo = $_POST['id_activo'];
    $ram = $_POST['ram'];
    $almacenamiento = $_POST['almacenamiento'];
    $sistema_operativo = $_POST['sistema_operativo'];
    $nombre_dominio = $_POST['nombre_dominio'];
    $id_usuario = $_SESSION['id_usuario']; // ID del usuario logueado

    // Corrección del bind_param
    $sql = "UPDATE especificaciones SET ram = ?, almacenamiento = ?, sistema_operativo = ?, nombre_dominio = ? WHERE id_activo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $ram, $almacenamiento, $sistema_operativo, $nombre_dominio, $id_activo);
    
    if ($stmt->execute()) {
        // Registrar la actualización en el historial
        $accion = "Actualización de activo con id $id_activo: RAM: $ram GB, Almacenamiento: $almacenamiento GB, SO: $sistema_operativo, Dominio: $nombre_dominio";
        $sql_historial = "INSERT INTO historial (id_usuario, accion) VALUES (?, ?)";
        $stmt_historial = $conn->prepare($sql_historial);
        $stmt_historial->bind_param("is", $id_usuario, $accion);
        $stmt_historial->execute();

        
        header("Location: hojadevida.php?id_activo=" . $id_activo);
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => "Error al actualizar los datos"]);
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
    <title>Actualizar Información del Equipo</title>
    <link rel="stylesheet" href="css/actualizar.css">
</head>
<body>
    <div class="container">
        <h2>Actualizar Información del Equipo</h2>
        <form id="updateForm" method="POST">
            <label for="ram">RAM (GB):</label>
            <input type="number" id="ram" name="ram" required>

            <label for="almacenamiento">Almacenamiento (GB):</label>
            <input type="number" id="almacenamiento" name="almacenamiento" required>

            <label for="sistema_operativo">Sistema Operativo:</label>
            <input type="text" id="sistema_operativo" name="sistema_operativo" required>

            <label for="nombre_dominio">Nombre de Dominio:</label>
            <input type="text" id="nombre_dominio" name="nombre_dominio" required>

            <input type="hidden" id="id_activo" name="id_activo" value="<?= $_GET['id_activo'] ?? '' ?>"> 
            <button type="submit" class="btn">Actualizar</button>
        </form>
        <p id="status"></p>
    </div>
</body>
</html>
