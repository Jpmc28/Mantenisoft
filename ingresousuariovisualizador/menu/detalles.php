<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "No autorizado"]);
    exit();
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mantenisoft';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    echo json_encode(["error" => "Error de conexión: " . $conn->connect_error]);
    exit();
}

if (!isset($_GET['piso'])) {
    echo json_encode(["error" => "No se proporcionó el piso"]);
    exit();
}

$piso = $_GET['piso'];

$sql = "SELECT m.fecha_mantenimiento, u.nombre AS tecnico, m.descripcion, m.nombre_responsable, 
               m.cargo_responsable, m.firma_tecnico, ac.NPlaca AS NPlaca
        FROM mantenimientos m
        JOIN usuarios u ON m.id_usuario = u.id_usuario
        JOIN activos ac ON m.id_activo = ac.id_activo
        JOIN areas a ON ac.id_area = a.id_area
        JOIN pisos p ON a.id_piso = p.id_piso
        WHERE p.nombre_piso = ? AND m.estado = 'Completado'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $piso);
$stmt->execute();
$result = $stmt->get_result();

$detalles = [];
while ($row = $result->fetch_assoc()) {
    $descripcion = json_decode($row["descripcion"], true);
    if ($descripcion) {
        // Filtrar solo tareas que fueron completadas (1)
        $softwareCompletado = array_filter($descripcion['software'] ?? [], fn($value) => $value == 1);
        $hardwareCompletado = array_filter($descripcion['hardware'] ?? [], fn($value) => $value == 1);
        
        if (!empty($softwareCompletado) || !empty($hardwareCompletado)) {
            $row["descripcion"] = [
                "software" => array_keys($softwareCompletado),
                "hardware" => array_keys($hardwareCompletado)
            ];
            $detalles[] = $row;
        }
    }
}

$stmt->close();
$conn->close();

echo json_encode($detalles);
?>
