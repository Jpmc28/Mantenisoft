<?php

$host = 'localhost'; 
$user = 'root';      
$password = '';      
$database = 'mantenisoft';  

$conn = new mysqli($host, $user, $password, $database);

session_start();

// Verificar si el piso se recibió
if (!isset($_GET['piso']) || empty($_GET['piso'])) {
    echo json_encode([]);
    exit();
}

$piso = $_GET['piso'];
$piso_id = "10" . str_pad($piso, 2, "0", STR_PAD_LEFT); // Convierte "1" en "1010", "S1" en "1001", etc.

$sql = "SELECT a.id_activo, a.nombre, a.tipo, a.estado, a.NPlaca, ar.nombre_area
        FROM activos a
        JOIN areas ar ON a.id_area = ar.id_area
        JOIN pisos p ON ar.id_piso = p.id_piso
        WHERE p.id_piso = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $piso_id);
$stmt->execute();
$resultado = $stmt->get_result();

$activos = [];
while ($fila = $resultadfetch_assoc()) {
    $activos[] = $fila;
}

$stmt->close();
$conexion->close();

// Devolver los datos en formato JSON
echo json_encode($activos);