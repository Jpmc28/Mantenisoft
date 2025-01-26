<?php
//conexion
$host = 'localhost'; 
$user = 'root';      
$password = '';      
$database = 'mantenisoft'; 


$conn = new mysqli($host, $user, $password, $database);

session_start();

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $cargo = $_POST['cargo'];
    $email = $_POST['email'];
    $cedula = $_POST['cedula'];

    $sql = "UPDATE administratoruser SET Nombre = ?, Rol = ?, InstitucionalEmail = ? WHERE IdCedula = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssss", $nombre, $cargo, $email, $cedula);
        
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
