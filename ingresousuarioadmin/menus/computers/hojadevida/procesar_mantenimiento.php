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
    die("<p style='color: red;'>Error de conexión: " . $conn->connect_error . "</p>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_mantenimiento = intval($_POST['id_mantenimiento']);
    
    // Determinar el estado del mantenimiento según el botón presionado
    $estado = ($_POST['accion'] === "cerrar") ? "Completado" : "En Proceso";

    // Función para capturar los valores de los checkboxes y convertirlos en un array asociativo
    function getCheckboxValues($name) {
        return isset($_POST[$name]) ? array_fill_keys($_POST[$name], 1) : [];
    }

    // Convertir los checkboxes en formato JSON para la base de datos
    $descripcion = json_encode([
        "software" => getCheckboxValues('software'),
        "hardware" => getCheckboxValues('hardware')
    ]);

    // Capturar el resto de la información del formulario
    $nombre_responsable = $_POST['nombre_responsable'];
    $cargo_responsable = $_POST['cargo_responsable'];
    $firma_tecnico = $_POST['firma'];

    // Actualizar el mantenimiento en la base de datos
    $sql = "UPDATE mantenimientos SET descripcion=?, nombre_responsable=?, cargo_responsable=?, firma_tecnico=?, estado=? WHERE id_mantenimiento=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $descripcion, $nombre_responsable, $cargo_responsable, $firma_tecnico, $estado, $id_mantenimiento);

    if ($stmt->execute()) {
        // Redirigir con mensaje de éxito
        header("Location: ../../computers.php?mensaje=Mantenimiento actualizado con éxito");
        exit();
    } else {
        echo "<p style='color: red;'>Error al actualizar el mantenimiento: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>
