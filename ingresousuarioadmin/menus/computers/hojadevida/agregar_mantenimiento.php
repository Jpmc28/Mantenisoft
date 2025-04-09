<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipo_usuario'] != 'admin' && $_SESSION['tipo_usuario'] != 'super_usuario')) {
  header("Location: ../../../../index.php");
  exit();
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mantenisoft';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("<p style='color: red;'>Error de conexión: " . $conn->connect_error . "</p>");
}

if (!empty($_GET['id_activo'])) {
    $_SESSION['id_activo'] = intval($_GET['id_activo']);
}

if (!isset($_SESSION['id_activo']) || $_SESSION['id_activo'] <= 0) {
    die("<p style='color: red;'>Error: No se ha especificado un activo válido para mantenimiento.</p>");
}

$id_usuario = $_SESSION['id_usuario'];
$id_activo = $_SESSION['id_activo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function getCheckboxValues($name) {
        global $_POST;
        $checkboxes = [
            "borrar_temporales" => 0,
            "desfragmentar_disco" => 0,
            "ajustar_rendimiento" => 0,
            "verificar_antivirus" => 0,
            "actualizar_windows" => 0,
            "verificar_explorador" => 0,
            "limpieza_piezas" => 0,
            "soplado_cpu" => 0,
            "limpieza_perifericos" => 0,
            "organizar_cables" => 0,
            "verificar_guaya" => 0
        ];

        if (isset($_POST[$name]) && is_array($_POST[$name])) {
            foreach ($_POST[$name] as $valor) {
                if (array_key_exists($valor, $checkboxes)) {
                    $checkboxes[$valor] = 1;
                }
            }
        }

        return $checkboxes;
    }

    $descripcion = json_encode([
        "software" => getCheckboxValues('software'),
        "hardware" => getCheckboxValues('hardware')
    ]);

    $nombre_responsable = $_POST['nombre_responsable'] ?? '';
    $cargo_responsable = $_POST['cargo_responsable'] ?? '';
    $firma_tecnico = $_POST['firma'] ?? '';
    $estado = $_POST['estado_mantenimiento'] ?? 'Completado';

    $sql = "INSERT INTO mantenimientos (id_activo, id_usuario, fecha_mantenimiento, descripcion, nombre_responsable, cargo_responsable, firma_tecnico, estado) 
            VALUES (?, ?, NOW(), ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssss", $id_activo, $id_usuario, $descripcion, $nombre_responsable, $cargo_responsable, $firma_tecnico, $estado);

    if ($stmt->execute()) {
        $accion = "El usuario $id_usuario realizó un mantenimiento al activo $id_activo";
        $sql_historial = "INSERT INTO historial (id_usuario, accion, fecha) VALUES (?, ?, NOW())";
        $stmt_historial = $conn->prepare($sql_historial);
        $stmt_historial->bind_param("is", $id_usuario, $accion);
        $stmt_historial->execute();
        
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
    <link rel="stylesheet" href="css/styles.css"> 
</head>
<body>

<form id="mantenimientoForm" action="agregar_mantenimiento.php" method="POST">
    <h2>Registro de Mantenimiento</h2>

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

    <div class="datos-contenedor">
        <label>Nombre del Responsable:</label>
        <input type="text" name="nombre_responsable">

        <label>Cargo del Responsable:</label>
        <input type="text" name="cargo_responsable">

        <h3>Firma Digital</h3>
        <canvas id="firmaCanvas"></canvas>
        <button type="button" id="limpiarFirma">Borrar Firma</button>
        <input type="hidden" name="firma" id="firmaInput">

        <input type="hidden" name="estado_mantenimiento" id="estado_mantenimiento" value="Completado">

        <button type="submit" id="guardarMantenimiento">Cerrar Mantenimiento</button>
        <button type="submit" id="dejarEnProceso">Dejar en Proceso</button>
    </div>
</form>

<script>
    const canvas = document.getElementById('firmaCanvas');
    const firmaInput = document.getElementById('firmaInput');
    const limpiarFirma = document.getElementById('limpiarFirma');

    canvas.width = 400;
    canvas.height = 200;

    const ctx = canvas.getContext('2d');
    let dibujando = false;

    canvas.addEventListener('mousedown', (e) => {
    dibujando = true;
    dibujar(e); // Para marcar el inicio
    });

    canvas.addEventListener('mouseup', () => {
    dibujando = false;
    ctx.beginPath();
    // Guardar la firma como Data URL cuando se suelta el mouse
    firmaInput.value = canvas.toDataURL(); // Guardar la firma en el input
    });

    canvas.addEventListener('mousemove', (e) => {
    if (dibujando) {
        dibujar(e);
    }
    });

    function dibujar(e) {
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = 'black';

    ctx.lineTo(x, y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(x, y);
    }

    // Botón para limpiar la firma
    limpiarFirma.addEventListener('click', function() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    firmaInput.value = ''; // Limpiar el input de la firma
    });

    // Aquí tus otros botones de guardar mantenimiento y dejar en proceso:
    document.getElementById('guardarMantenimiento').addEventListener('click', function() {
    document.getElementById('estado_mantenimiento').value = 'Completado';
    });

    document.getElementById('dejarEnProceso').addEventListener('click', function() {
    document.getElementById('estado_mantenimiento').value = 'En proceso';
    });
</script>

</body>
</html>
