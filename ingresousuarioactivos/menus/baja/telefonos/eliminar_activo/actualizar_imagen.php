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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["imagen"])) {
    $id_activo = intval($_POST["id_activo"]);
    $usuario = $_SESSION["id_usuario"];

    // Validar si hubo error al subir el archivo
    if ($_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
        die("Error al subir la imagen. Código: " . $_FILES["imagen"]["error"]);
    }

    // Leer el contenido de la imagen
    $imagen = file_get_contents($_FILES["imagen"]["tmp_name"]);

    // Actualizar la imagen en la base de datos
    $sql = "UPDATE activos SET imagen = ? WHERE id_activo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("bi", $imagen, $id_activo);
    
    // Vincular los parámetros correctamente para datos binarios
    $stmt->send_long_data(0, $imagen);
    
    if ($stmt->execute()) {
        // Registrar en historial
        $sql_historial = "INSERT INTO historial (id_usuario, accion, detalles, fecha) VALUES (?, 'Actualización de Imagen', ?, NOW())";
        $stmt_historial = $conn->prepare($sql_historial);
        $detalles = "Actualizó la imagen del activo ID: " . $id_activo;
        $stmt_historial->bind_param("is", $usuario, $detalles);
        $stmt_historial->execute();
        
        // Redirigir después de actualizar
        header("Location: elimarcomputador.php?id_activo=" . $id_activo);
        exit();  
    } else {
        echo "Error al actualizar la imagen en la base de datos.";
    }
}

$conn->close();
?>