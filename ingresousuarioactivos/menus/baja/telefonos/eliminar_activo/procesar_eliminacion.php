<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipo_usuario'] != 'activos' && $_SESSION['tipo_usuario'] != 'super_usuario')) {
  header("Location: ../../../../../index.php");
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_activo"])) {
    $id_activo = intval($_POST["id_activo"]); // Convertir el ID a entero para evitar inyecciones SQL

    // Conexión a la base de datos
    $conn = new mysqli($host, $user, $password, $database);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Query para actualizar el estado y la fecha_baja
    $sql = "UPDATE activos SET estado = 'dado de baja', fecha_baja = NOW() WHERE id_activo = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_activo);

    if ($stmt->execute()) {
        echo "El activo ha sido marcado como 'dado de baja'.";
        header("Location: ../../../../inicioactivos.php"); // Redirigir a la lista de activos (ajusta según tu estructura)
        exit();
    } else {
        echo "Error al actualizar el activo: " . $conn->error;
    }

    // Cerrar conexiones
    $stmt->close();
    $conn->close();
} else {
    echo "Solicitud no válida.";
}
?>

