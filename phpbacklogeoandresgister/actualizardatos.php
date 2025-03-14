<?php
session_start();

// Verificar si el usuario está autenticado y es admin
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Conexión a la base de datos
$host = 'localhost'; 
$user = 'root';      
$password = 'root';      
$database = 'mantenisoft'; 

$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar datos del formulario
    $nombre = $_POST['nombre'];
    $cargo = $_POST['cargo'];
    $email = $_POST['email'];
    $cedula = $_POST['cedula'];
    
    // Recuperar el id_usuario de la sesión
    $id_usuario = $_SESSION['id_usuario'];

    // Preparar la consulta de actualización
    $sql = "UPDATE usuarios SET nombre = ?, Rol = ?, correo = ?, Cedula = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssi", $nombre, $cargo, $email, $cedula, $id_usuario);
        
        if ($stmt->execute()) {
            header("Location: ../index.php"); 
            exit();
        } else {
            echo "Error al actualizar los datos: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}

$conn->close();
?>

