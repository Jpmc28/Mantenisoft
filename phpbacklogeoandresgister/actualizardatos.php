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
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];
    $numero = $_POST['numero'];
    $direccion = $_POST['direccion'];

    $sql = "UPDATE EstudiantesKonradLorenz SET Nombres = ?, Telefono = ?, Direccion = ? WHERE CorreoInstitucional = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssss", $nombre, $numero, $direccion, $email);
        
        if ($stmt->execute()) {
            echo "Datos actualizados exitosamente.";
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
