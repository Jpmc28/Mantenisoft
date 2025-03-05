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

// Verificar si hay un mantenimiento "En Proceso"
$sql = "SELECT descripcion, nombre_responsable, cargo_responsable, firma_tecnico FROM mantenimientos WHERE id_activo = ? AND estado = 'En Proceso' ORDER BY fecha_mantenimiento DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_activo);
$stmt->execute();
$result = $stmt->get_result();
$mantenimiento_pendiente = $result->fetch_assoc();

$descripcion = $mantenimiento_pendiente ? json_decode($mantenimiento_pendiente['descripcion'], true) : null;
$nombre_responsable = $mantenimiento_pendiente['nombre_responsable'] ?? '';
$cargo_responsable = $mantenimiento_pendiente['cargo_responsable'] ?? '';
$firma_tecnico = $mantenimiento_pendiente['firma_tecnico'] ?? '';

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Mantenimiento</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<form id="mantenimientoForm" action="agregar_mantenimiento.php" method="POST">
    <h2>Registro de Mantenimiento</h2>
    <div class="mantenimiento-contenedor">
        <div class="columna">
            <h3>Software</h3>
            <?php
            $software_tareas = ['borrar_temporales', 'desfragmentar_disco', 'ajustar_rendimiento', 'verificar_antivirus', 'actualizar_windows', 'verificar_pagina'];
            foreach ($software_tareas as $tarea) {
                $checked = isset($descripcion['software'][$tarea]) && $descripcion['software'][$tarea] ? 'checked' : '';
                echo "<label><input type='checkbox' name='software[]' value='$tarea' $checked> " . ucfirst(str_replace('_', ' ', $tarea)) . "</label>";
            }
            ?>
        </div>
        <div class="columna">
            <h3>Hardware</h3>
            <?php
            $hardware_tareas = ['limpieza_piezas', 'soplado_cpu', 'limpieza_perifericos', 'organizacion_cables', 'verificacion_guaya'];
            foreach ($hardware_tareas as $tarea) {
                $checked = isset($descripcion['hardware'][$tarea]) && $descripcion['hardware'][$tarea] ? 'checked' : '';
                echo "<label><input type='checkbox' name='hardware[]' value='$tarea' $checked> " . ucfirst(str_replace('_', ' ', $tarea)) . "</label>";
            }
            ?>
        </div>
    </div>
    <div class="datos-contenedor">
        <label>Nombre del Responsable:</label>
        <input type="text" name="nombre_responsable" value="<?php echo $nombre_responsable; ?>" required>

        <label>Cargo del Responsable:</label>
        <input type="text" name="cargo_responsable" value="<?php echo $cargo_responsable; ?>" required>

        <h3>Firma Digital</h3>
        <canvas id="firmaCanvas"></canvas>
        <button type="button" id="limpiarFirma">Borrar Firma</button>
        <input type="hidden" name="firma" id="firmaInput" value="<?php echo htmlspecialchars($firma_tecnico); ?>">

        <input type="hidden" name="estado_mantenimiento" id="estado_mantenimiento" value="Completado">

        <button type="submit" id="guardarMantenimiento">Cerrar Mantenimiento</button>
        <button type="submit" id="dejarEnProceso">Dejar en Proceso</button>
    </div>
</form>
<script>
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

    document.getElementById('dejarEnProceso').addEventListener('click', function () {
        document.getElementById('estado_mantenimiento').value = 'En Proceso';
    });

    document.getElementById('guardarMantenimiento').addEventListener('click', function () {
        document.getElementById('estado_mantenimiento').value = 'Completado';
    });
</script>
</body>
</html>

