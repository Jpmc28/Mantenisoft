<?php
//conexion
$host = 'localhost'; 
$user = 'root';      
$password = '';      
$database = 'mantenisoft'; 


$conn = new mysqli($host, $user, $password, $database);

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $psw = $_POST['password'];

    $sql = "SELECT * FROM administratoruser WHERE InstitucionalEmail = ? AND  IdCedula = ?;";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("ss", $email, $psw);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['Name'] = $user['Name'];
        $_SESSION['IdCedula'] = $user['IdCedula'];
        $_SESSION['Rol'] = $user['Rol'];
        $_SESSION['InstitucionalEmail'] = $user['InstitucionalEmail'];
        header("Location: ../ingresousuarioadmin/inicioadmin.php"); 
        exit();
    } else {
        header("Location: ../index.php");
        echo '<h1 class="bad">ERROR EN LA AUTENTIFICACION</h1>';
    }
    $stmt->close();
}

$conn->close();

?>
