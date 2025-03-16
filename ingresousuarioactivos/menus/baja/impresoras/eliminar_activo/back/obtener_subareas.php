<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipo_usuario'] != 'activos' && $_SESSION['tipo_usuario'] != 'super_usuario')) {
  header("Location: ../../../../../../index.php");
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

if (isset($_POST['area_id'])) {
    $area_id = $_POST['area_id'];

    // Consulta para obtener subáreas filtradas según el área seleccionada
    $sql = "SELECT id_area_especifica, area_especifica_nombre FROM areas_especificas WHERE id_area = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $area_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">(Opcional) Seleccione una subárea</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id_area_especifica'].'">'.$row['area_especifica_nombre'].'</option>';
    }

    $stmt->close();
}

$conn->close();
?>