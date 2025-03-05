<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'admin') {
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


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_mantenimiento'])) {
    $id_mantenimiento = $_POST['id_mantenimiento'];

    // Obtener los datos completos del mantenimiento en proceso
    $sql = "SELECT * FROM mantenimientos WHERE id_mantenimiento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_mantenimiento);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $mantenimiento = $resultado->fetch_assoc();

        // Guardar los datos en la sesión para usarlos en el formulario
        $_SESSION['mantenimiento_en_proceso'] = $mantenimiento;
        header("Location: retomar_mantenicheck.php"); // Redirigir al formulario
        exit();
    } else {
        echo "Error: No se encontró el mantenimiento.";
    }
}
?>

