<?php
// Conexión a la base de datos
$host = 'localhost'; 
$user = 'root';      
$password = '';      
$database = 'mantenisoft';  

$conn = new mysqli($host, $user, $password, $database);

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $tipo = $_POST["tipo"];
    $estado = $_POST["estado"];
    $area = $_POST["area"];
    $id_usuario = $_POST["id_usuario"];
    $NPlaca = $_POST["NPlaca"];

    //procesar imagen

    $imagen_nombre = $_FILES["imagen"]["name"];
    $imagen_tmp = $_FILES["imagen"]["tmp_name"];
    $imagen_tipo = $_FILES["imagen"]["type"];
    $imagen_data = file_get_contents($imagen_tmp);
    //insertar en la bd
    $sql = "insert into activos (nombre, tipo, estado, id_area, id_usuario, NPlaca, imagen) values (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nombre, $tipo, $estado, $area, $id_usuario, $NPlaca, $imagen_data);

    if ($stmt->execute()) {
        echo "imagen subida exitosamente.";
    } else{
        echo "error al subir la imagen: " . $conn->error;
    }
    $stmt->close();
    $conn->close();

}
?>
