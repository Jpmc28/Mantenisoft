<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'activos') {
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

if (!isset($_GET['id_activo'])) {
    die("ID de activo no especificado.");
}
$id_activo = intval($_GET['id_activo']);

$sql = "SELECT imagen FROM activos WHERE id_activo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_activo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    die("Imagen no encontrada.");
}

$equipo = $resultado->fetch_assoc();
$imagen = $equipo['imagen'];

if (!$imagen) {
    die("No hay imagen disponible.");
}

header("Content-Type: image/jpeg"); // Cambia a "image/png" si es PNG
echo $imagen;
?>