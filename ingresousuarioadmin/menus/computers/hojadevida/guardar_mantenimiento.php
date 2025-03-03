<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: ../../../../index.php");
    exit();
}

// Conectar a la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mantenisoft';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("<p style='color: red;'>Error de conexión: " . $conn->connect_error . "</p>");
}

// Guardar id_activo en la sesión si viene por GET
if (!empty($_GET['id_activo'])) {
    $_SESSION['id_activo'] = intval($_GET['id_activo']);
}

// Verificar que id_activo está en sesión
if (!isset($_SESSION['id_activo']) || $_SESSION['id_activo'] <= 0) {
    die("<p style='color: red;'>Error: No se ha especificado un activo válido para mantenimiento.</p>");
}

$id_usuario = $_SESSION['id_usuario'];
$id_activo = $_SESSION['id_activo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $borrar_temporales = isset($_POST['borrar_temporales']) ? 1 : 0;
    $desfragmentar_disco = isset($_POST['desfragmentar_disco']) ? 1 : 0;
    $ajustar_rendimiento = isset($_POST['ajustar_rendimiento']) ? 1 : 0;
    $verificar_antivirus = isset($_POST['verificar_antivirus']) ? 1 : 0;
    $actualizar_windows = isset($_POST['actualizar_windows']) ? 1 : 0;
    $verificar_pagina = isset($_POST['verificar_pagina']) ? 1 : 0;
    $limpieza_piezas = isset($_POST['limpieza_piezas']) ? 1 : 0;
    $soplado_cpu = isset($_POST['soplado_cpu']) ? 1 : 0;
    $limpieza_perifericos = isset($_POST['limpieza_perifericos']) ? 1 : 0;
    $organizacion_cables = isset($_POST['organizacion_cables']) ? 1 : 0;
    $verificacion_guaya = isset($_POST['verificacion_guaya']) ? 1 : 0;
    $firma_tecnico = $_POST['firma_tecnico'];
    $nombre_responsable = $_POST['nombre_responsable'];
    $cargo_responsable = $_POST['cargo_responsable'];

    // Crear la descripción en formato JSON
    $descripcion = json_encode([
        "software" => [
            "borrar_temporales" => $borrar_temporales,
            "desfragmentar_disco" => $desfragmentar_disco,
            "ajustar_rendimiento" => $ajustar_rendimiento,
            "verificar_antivirus" => $verificar_antivirus,
            "actualizar_windows" => $actualizar_windows,
            "verificar_pagina" => $verificar_pagina
        ],
        "hardware" => [
            "limpieza_piezas" => $limpieza_piezas,
            "soplado_cpu" => $soplado_cpu,
            "limpieza_perifericos" => $limpieza_perifericos,
            "organizacion_cables" => $organizacion_cables,
            "verificacion_guaya" => $verificacion_guaya
        ]
    ]);

    // Insertar en la tabla de mantenimientos
    $sql = "INSERT INTO mantenimientos (id_activo, id_usuario, fecha_mantenimiento, descripcion, nombre_responsable, cargo_responsable, firma_tecnico) 
            VALUES (?, ?, NOW(), ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissss", $id_activo, $id_usuario, $descripcion, $nombre_responsable, $cargo_responsable, $firma_tecnico);

    if ($stmt->execute()) {
        // Insertar en el historial
        $accion = "El usuario $id_usuario realizó un mantenimiento al activo $id_activo";
        $sql_historial = "INSERT INTO historial (id_usuario, accion, fecha) VALUES (?, ?, NOW())";
        $stmt_historial = $conn->prepare($sql_historial);
        $stmt_historial->bind_param("is", $id_usuario, $accion);
        $stmt_historial->execute();

        // Redirigir con mensaje de éxito
        header("Location: dashboard.php?mensaje=Mantenimiento registrado con éxito");
        exit();
    } else {
        echo "<p style='color: red;'>Error al registrar el mantenimiento: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $stmt_historial->close();
}

$conn->close();
?>
