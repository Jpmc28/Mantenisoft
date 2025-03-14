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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_activo = $_POST['id_activo'] ?? null;
    $ram = $_POST['ram'] ?? null;
    $almacenamiento = $_POST['almacenamiento'] ?? null;
    $sistema_operativo = $_POST['sistema_operativo'] ?? null;
    $nombre_dominio = $_POST['nombre_dominio'] ?? null;
    $procesador = $_POST['procesador'] ?? null;
    $software_instalado = $_POST['software_instalado'] ?? null;
    $id_usuario = $_SESSION['id_usuario']; // ID del usuario logueado

    if (empty($id_activo) || !is_numeric($id_activo)) {
        die("Error: ID de activo inválido o no recibido.");
    }

    // Verificar si ya existen datos en especificaciones
    $sql_check = "SELECT * FROM especificaciones WHERE id_activo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id_activo);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row = $result_check->fetch_assoc();
    $stmt_check->close();

    if ($row) {
        // Si existen datos, usar los valores actuales si el usuario no los modifica
        $ram = !empty($ram) ? $ram : $row['ram'];
        $almacenamiento = !empty($almacenamiento) ? $almacenamiento : $row['almacenamiento'];
        $sistema_operativo = !empty($sistema_operativo) ? $sistema_operativo : $row['sistema_operativo'];
        $nombre_dominio = !empty($nombre_dominio) ? $nombre_dominio : $row['nombre_dominio'];
        $procesador = !empty($procesador) ? $procesador : $row['procesador'];
        $software_instalado = !empty($software_instalado) ? $software_instalado : $row['software_instalado'];

        // Actualizar los datos
        $sql = "UPDATE especificaciones 
                SET ram = ?, almacenamiento = ?, sistema_operativo = ?, nombre_dominio = ?, procesador = ?, software_instalado = ? 
                WHERE id_activo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $ram, $almacenamiento, $sistema_operativo, $nombre_dominio, $procesador, $software_instalado, $id_activo);
        $accion = "Actualización de especificaciones del activo $id_activo.";
    } else {
        // Si no existen datos, hacer INSERT
        $sql = "INSERT INTO especificaciones (id_activo, ram, almacenamiento, sistema_operativo, nombre_dominio, procesador, software_instalado) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssss", $id_activo, $ram, $almacenamiento, $sistema_operativo, $nombre_dominio, $procesador, $software_instalado);
        $accion = "Registro de nuevas especificaciones para el activo $id_activo.";
    }

    // Ejecutar la consulta de inserción/actualización
    if ($stmt->execute()) {
        // Registrar la acción en el historial
        $sql_historial = "INSERT INTO historial (id_usuario, accion, detalles) VALUES (?, ?, ?)";
        $stmt_historial = $conn->prepare($sql_historial);
        $detalles = "RAM: $ram GB, Almacenamiento: $almacenamiento GB, SO: $sistema_operativo, Dominio: $nombre_dominio, Procesador: $procesador, Software: $software_instalado";
        $stmt_historial->bind_param("iss", $id_usuario, $accion, $detalles);
        $stmt_historial->execute();
        $stmt_historial->close();

        $stmt->close();
        $conn->close();
        
        // Redirigir con éxito
        header("Location: hojadevida.php?id_activo=" . $id_activo . "&success=1");
        exit();
    } else {
        die("Error al ejecutar la consulta: " . $stmt->error);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Información del Equipo</title>
    <link rel="stylesheet" href="css/actualizar.css">
</head>
<body>
    <div class="container">
        <h2>Actualizar Información del Equipo</h2>
        <form id="updateForm" method="POST">
            <label for="ram">RAM (GB):</label>
            <input type="number" id="ram" name="ram" >

            <label for="almacenamiento">Almacenamiento (GB):</label>
            <input type="number" id="almacenamiento" name="almacenamiento" >

            <label for="sistema_operativo">Sistema Operativo:</label>
            <input type="text" id="sistema_operativo" name="sistema_operativo" >

            <label for="procesador">Procesador:</label>
            <input type="text" id="procesador" name="procesador" >

            <label for="software_instalado">Software Especial Instalado:</label>
            <input type="text" id="software_instalado" name="software_instalado" >

            <label for="nombre_dominio">Nombre de Dominio:</label>
            <input type="text" id="nombre_dominio" name="nombre_dominio" >

            <input type="hidden" id="id_activo" name="id_activo" value="<?= $_GET['id_activo'] ?? '' ?>"> 
            <button type="submit" class="btn">Actualizar</button>
        </form>
        <p id="status"></p>
    </div>
</body>
</html>
