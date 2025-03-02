<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'activos') {
    echo json_encode(["error" => "No autorizado"]);
    exit();
}

// Configuración de la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mantenisoft';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

// Obtener el ID del usuario actual
$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener los activos insertados por el usuario
$sql = "SELECT nombre, NPlaca FROM activos WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

// Convertir los resultados a un array
$activos = [];
while ($fila = $resultado->fetch_assoc()) {
    $activos[] = $fila;
}

$stmt->close();
$conn->close();

// Enviar respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($activos);
?>
