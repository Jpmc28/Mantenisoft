<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$database = "mantenisoft";
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = strtoupper($_POST["nombre"]);
    $tipo = $_POST["tipo"];
    $estado = "activo";  // Estado fijo para ingresar activos
    $area = $_POST["area"];
    $subarea = $_POST["subarea"] ?: NULL; // Subárea opcional
    $id_usuario = $_SESSION["id_usuario"];
    $NPlaca = strtoupper($_POST["NPlaca"]);

    // Procesar imagen
    $imagen_data = NULL;
    if (!empty($_FILES["imagen"]["tmp_name"])) {
        $imagen_data = file_get_contents($_FILES["imagen"]["tmp_name"]);
    }

    // Insertar en la base de datos
    $sql = "INSERT INTO activos (nombre, tipo, estado, id_area, id_areas_especificas, id_usuario, NPlaca, imagen)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisss", $nombre, $tipo, $estado, $area, $subarea, $id_usuario, $NPlaca, $imagen_data);

    if ($stmt->execute()) {
        echo "Imagen subida exitosamente.";
    } else {
        echo "Error al subir la imagen: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>

