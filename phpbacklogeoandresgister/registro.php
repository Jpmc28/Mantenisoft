<?php
// Conexión a la base de datos
$host = 'localhost'; 
$user = 'root';      
$password = '';      
$database = 'mantenisoft';  

$conn = new mysqli($host, $user, $password, $database);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar que las contraseñas coincidan
    if ($password !== $confirm_password) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    $sql = "SELECT contraseña FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!empty($row['contraseña'])) {
            echo "El correo electrónico ya tiene una contraseña asignada.";
        } else {
            // Cifrar la nueva contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Asignar la nueva contraseña cifrada
            $sql = "UPDATE usuarios SET contraseña = ? WHERE correo = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $hashed_password, $email);
            if ($stmt->execute()) {
                header("Location: ../index.php");
            } else {
                header("Location: ../index.php");
                echo "Error al asignar la contraseña.";
            }
        }
    } else {
        echo "El correo electrónico no existe en la base de datos.";
    }
    $stmt->close();
}

$conn->close();
