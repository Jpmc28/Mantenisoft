<?php
// Conexión a la base de datos
$host = 'localhost'; 
$user = 'root';      
$password = '';      
$database = 'mantenisoft';  

$conn = new mysqli($host, $user, $password, $database);

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $psw = $_POST['password'];

    // Consulta SQL SIN verificar la contraseña aún
    $sql = "SELECT id_usuario, nombre, Cedula, tipo_usuario, correo, contraseña, Rol FROM usuarios WHERE correo = ? and contraseña = ?;";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("ss", $email, $psw);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['Cedula'] = $user['Cedula'];
            $_SESSION['tipo_usuario'] = $user['tipo_usuario'];
            $_SESSION['Rol'] = $user['Rol'];
            $_SESSION['correo'] = $user['correo'];
        switch ($user['tipo_usuario']) {
            case 'admin':
                header("Location: ../ingresousuarioadmin/inicioadmin.php");
                break;
            case 'activos':
                header("Location: ../ingresousuarioactivos/inicioactivos.php");
                break;
            case 'visualizador':
                header("Location: ../ingresousuariovisualizador/iniciovisualizador.php");
                break;
            default:
                header("Location: ../index.php"); 
            }
        exit();
    } else {
        header("Location: ../index.php");
        echo '<h1 class="bad">ERROR EN LA AUTENTIFICACION</h1>';
    }
    $stmt->close();
}

$conn->close();


