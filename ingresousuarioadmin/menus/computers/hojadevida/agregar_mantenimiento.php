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
    $firma_tecnico = isset($_POST['firma']) ? $_POST['firma'] : '';
        if (empty($firma_tecnico)) {
            die("Error: La firma es obligatoria.");
        }
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
    $estado = $_POST['estado_mantenimiento'];

    // Insertar en la tabla de mantenimientos
    $sql = "INSERT INTO mantenimientos (id_activo, id_usuario, fecha_mantenimiento, descripcion, nombre_responsable, cargo_responsable, firma_tecnico, estado) 
            VALUES (?, ?, NOW(), ?, ?, ?, ?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssss", $id_activo, $id_usuario, $descripcion, $nombre_responsable, $cargo_responsable, $firma_tecnico, $estado);

    if ($stmt->execute()) {
        // Insertar en el historial
        $accion = "El usuario $id_usuario realizó un mantenimiento al activo $id_activo";
        $sql_historial = "INSERT INTO historial (id_usuario, accion, fecha) VALUES (?, ?, NOW())";
        $stmt_historial = $conn->prepare($sql_historial);
        $stmt_historial->bind_param("is", $id_usuario, $accion);
        $stmt_historial->execute();

        // Redirigir con mensaje de éxito
        header("Location: ../../computers.php?mensaje=Mantenimiento registrado con éxito");
        die();
    } else {
        echo "<p style='color: red;'>Error al registrar el mantenimiento: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $stmt_historial->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Mantenimiento</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- 📌 Incluir el CSS -->
</head>
<body>

<form id="mantenimientoForm" action="agregar_mantenimiento.php" method="POST">
    <h2>Registro de Mantenimiento</h2>

    <!-- Contenedor en dos columnas -->
    <div class="mantenimiento-contenedor">
        <div class="columna">
            <h3>Software</h3>
            <label><input type="checkbox" name="software[]" value="borrar_temporales"> Borrar temporales</label>
            <label><input type="checkbox" name="software[]" value="desfragmentar_disco"> Desfragmentar y liberar disco</label>
            <label><input type="checkbox" name="software[]" value="ajustar_rendimiento"> Ajustar el rendimiento</label>
            <label><input type="checkbox" name="software[]" value="verificar_antivirus"> Verificar antivirus</label>
            <label><input type="checkbox" name="software[]" value="actualizar_windows"> Actualizaciones de Windows</label>
            <label><input type="checkbox" name="software[]" value="verificar_explorador"> Verificar página predeterminada del explorador</label>
        </div>

        <div class="columna">
            <h3>Hardware</h3>
            <label><input type="checkbox" name="hardware[]" value="limpieza_piezas"> Limpieza general de piezas internas</label>
            <label><input type="checkbox" name="hardware[]" value="soplado_cpu"> Soplado interno de CPU</label>
            <label><input type="checkbox" name="hardware[]" value="limpieza_perifericos"> Limpieza de periféricos</label>
            <label><input type="checkbox" name="hardware[]" value="organizar_cables"> Organización de cables</label>
            <label><input type="checkbox" name="hardware[]" value="verificar_guaya"> Organización y verificación de la guaya</label>
        </div>
    </div>

    <!-- Datos del Responsable -->
    <div class="datos-contenedor">
        <label>Nombre del Responsable:</label>
        <input type="text" name="nombre_responsable" required>

        <label>Cargo del Responsable:</label>
        <input type="text" name="cargo_responsable" required>

        <!-- Firma Digital -->
        <h3>Firma Digital</h3>
        <canvas id="firmaCanvas"></canvas>
        <button type="button" id="limpiarFirma">Borrar Firma</button>
        <input type="hidden" name="firma" id="firmaInput">

        <!-- Campo oculto para el estado -->
        <input type="hidden" name="estado_mantenimiento" id="estado_mantenimiento" value="Completado">

        <!-- Botones -->
        <button type="submit" id="guardarMantenimiento">Cerrar Mantenimiento</button>
        <button type="submit" id="dejarEnProceso">Dejar en Proceso</button>
    </div>
</form>

<script>
    // Manejo de la firma digital
    const canvas = document.getElementById('firmaCanvas');
    const ctx = canvas.getContext('2d');
    const firmaInput = document.getElementById('firmaInput');
    const limpiarFirma = document.getElementById('limpiarFirma');
    
    canvas.width = 400;
    canvas.height = 200;
    let dibujando = false;

    function comenzarDibujo(event) {
        dibujando = true;
        ctx.beginPath();
        ctx.moveTo(event.offsetX, event.offsetY);
    }

    function dibujar(event) {
        if (!dibujando) return;
        ctx.lineTo(event.offsetX, event.offsetY);
        ctx.stroke();
    }

    function finalizarDibujo() {
        dibujando = false;
        actualizarFirma();
    }

    function actualizarFirma() {
        firmaInput.value = canvas.toDataURL();
    }

    function limpiarCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        firmaInput.value = '';
    }

    canvas.addEventListener('mousedown', comenzarDibujo);
    canvas.addEventListener('mousemove', dibujar);
    canvas.addEventListener('mouseup', finalizarDibujo);
    limpiarFirma.addEventListener('click', limpiarCanvas);

    // Manejo del estado de mantenimiento
    document.getElementById('dejarEnProceso').addEventListener('click', function () {
        document.getElementById('estado_mantenimiento').value = 'En Proceso';
    });

    document.getElementById('guardarMantenimiento').addEventListener('click', function () {
        document.getElementById('estado_mantenimiento').value = 'Completado';
    });
</script>

</body>
</html>
