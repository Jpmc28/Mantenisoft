<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipo_usuario'] != 'admin' && $_SESSION['tipo_usuario'] != 'super_usuario')) {
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
    $id_activo = $_POST['id_activo'] ?? null;
    $tipo_cambio = $_POST['tipo_cambio'] ?? null;
    $id_usuario = $_SESSION['id_usuario'];
    $imagen_mantenimiento = null;

    if (empty($id_activo) || empty($tipo_cambio)) {
        die("Error: Datos incompletos.");
    }

    // Validar tipo de cambio
    $tipos_validos = ['cable energía', 'cable voz', 'bocina'];
    if (!in_array($tipo_cambio, $tipos_validos)) {
        die("Error: Tipo de cambio inválido.");
    }

    // Procesar imagen si se subió
    if (!empty($_FILES['imagen_mantenimiento']['tmp_name'])) {
        $imagen_mantenimiento = file_get_contents($_FILES['imagen_mantenimiento']['tmp_name']);
    }

    // Insertar en la base de datos
    $sql = "INSERT INTO mantenimientos_telefonos (id_activo, tipo_cambio, imagen_mantenimiento, id_usuario) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Enviar datos a la consulta
    $stmt->bind_param("issi", $id_activo, $tipo_cambio, $imagen_mantenimiento, $id_usuario);
    
    // Si la imagen no es nula, enviarla con `send_long_data`
    if ($imagen_mantenimiento !== null) {
        $stmt->send_long_data(2, $imagen_mantenimiento);
    }

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
