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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_activo = intval($_POST["id_activo"]);
    $id_area = intval($_POST["area"]);
    $id_subarea = isset($_POST["subarea"]) && !empty($_POST["subarea"]) ? intval($_POST["subarea"]) : NULL;
    $usuario = $_SESSION["id_usuario"];

    // Actualizar el área del activo
    $sql = "UPDATE activos SET id_area = ?, id_areas_especificas = ? WHERE id_activo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $id_area, $id_subarea, $id_activo);
    
    if ($stmt->execute()) {
        // Registrar en historial
        $sql_historial = "INSERT INTO historial (id_usuario, accion, detalles, fecha) VALUES (?, 'Cambio de Área', ?, NOW())";
        $stmt_historial = $conn->prepare($sql_historial);
        $detalles = "Se cambió el activo ID " . $id_activo . " a la área ID " . $id_area;
        $stmt_historial->bind_param("is", $usuario, $detalles);
        $stmt_historial->execute();
        
        // Redirigir después de actualizar
        header("Location: ../elimarcomputador.php?id_activo=" . $id_activo);
        exit();
    } else {
        echo "Error al actualizar el área.";
    }
}

$conn->close();
?>
