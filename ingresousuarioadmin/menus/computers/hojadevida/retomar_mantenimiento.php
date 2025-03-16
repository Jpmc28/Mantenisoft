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

if (!isset($_GET['id_mantenimiento'])) {
    die("<p style='color: red;'>Error: No se ha especificado un mantenimiento válido.</p>");
}

$id_mantenimiento = intval($_GET['id_mantenimiento']);

$sql = "SELECT * FROM mantenimientos WHERE id_mantenimiento = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_mantenimiento);
$stmt->execute();
$result = $stmt->get_result();
$mantenimiento = $result->fetch_assoc();

if (!$mantenimiento) {
    die("<p style='color: red;'>Error: No se encontró el mantenimiento.</p>");
}

$descripcion = json_decode($mantenimiento['descripcion'], true);
$software = $descripcion['software'] ?? [];
$hardware = $descripcion['hardware'] ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retomar Mantenimiento</title>
    <link rel="stylesheet" href="css/styles.css"> 
</head>
<body>

<form id="mantenimientoForm" action="procesar_mantenimiento.php" method="POST">
    <h2>Retomar Mantenimiento</h2>
    
    <input type="hidden" name="id_mantenimiento" value="<?php echo $id_mantenimiento; ?>">

    <div class="mantenimiento-contenedor">
        <div class="columna">
            <h3>Software</h3>
            <?php
            $softwareOpciones = [
                "borrar_temporales" => "Borrar temporales",
                "desfragmentar_disco" => "Desfragmentar y liberar disco",
                "ajustar_rendimiento" => "Ajustar el rendimiento",
                "verificar_antivirus" => "Verificar antivirus",
                "actualizar_windows" => "Actualizaciones de Windows",
                "verificar_explorador" => "Verificar página predeterminada del explorador"
            ];
            foreach ($softwareOpciones as $key => $label) {
                $checked = isset($software[$key]) && $software[$key] ? "checked" : "";
                echo "<label><input type='checkbox' name='software[]' value='$key' $checked> $label</label>";
            }
            ?>
        </div>

        <div class="columna">
            <h3>Hardware</h3>
            <?php
            $hardwareOpciones = [
                "limpieza_piezas" => "Limpieza general de piezas internas",
                "soplado_cpu" => "Soplado interno de CPU",
                "limpieza_perifericos" => "Limpieza de periféricos",
                "organizar_cables" => "Organización de cables",
                "verificar_guaya" => "Organización y verificación de la guaya"
            ];
            foreach ($hardwareOpciones as $key => $label) {
                $checked = isset($hardware[$key]) && $hardware[$key] ? "checked" : "";
                echo "<label><input type='checkbox' name='hardware[]' value='$key' $checked> $label</label>";
            }
            ?>
        </div>
    </div>

    <div class="datos-contenedor">
        <label>Nombre del Responsable:</label>
        <input type="text" name="nombre_responsable" value="<?php echo htmlspecialchars($mantenimiento['nombre_responsable']); ?>">

        <label>Cargo del Responsable:</label>
        <input type="text" name="cargo_responsable" value="<?php echo htmlspecialchars($mantenimiento['cargo_responsable']); ?>">

        <h3>Firma Digital</h3>
        <canvas id="firmaCanvas"></canvas>
        <button type="button" id="limpiarFirma">Borrar Firma</button>
        <input type="hidden" name="firma" id="firmaInput" value="<?php echo htmlspecialchars($mantenimiento['firma_tecnico']); ?>">

        <input type="hidden" name="estado_mantenimiento" id="estado_mantenimiento" value="En Proceso">

        <button type="submit" name="accion" value="guardar" id="guardarMantenimiento">Guardar Cambios</button>
        <button type="submit" name="accion" value="cerrar" id="cerrarMantenimiento">Cerrar Mantenimiento</button>
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

    canvas.addEventListener('mousedown', e => { dibujando = true; ctx.moveTo(e.offsetX, e.offsetY); });
    canvas.addEventListener('mousemove', e => { if (dibujando) { ctx.lineTo(e.offsetX, e.offsetY); ctx.stroke(); } });
    canvas.addEventListener('mouseup', () => { dibujando = false; firmaInput.value = canvas.toDataURL(); });
    limpiarFirma.addEventListener('click', () => { ctx.clearRect(0, 0, canvas.width, canvas.height); firmaInput.value = ''; });
</script>

</body>
</html>
