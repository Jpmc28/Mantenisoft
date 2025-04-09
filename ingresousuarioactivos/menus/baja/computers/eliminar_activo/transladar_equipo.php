<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipo_usuario'] != 'activos' && $_SESSION['tipo_usuario'] != 'super_usuario')) {
  header("Location: ../../../../../index.php");
  exit();
}

$host = "localhost";
$user = "root";
$password = "";
$database = "mantenisoft";
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener lista de áreas
$sql_areas = "SELECT id_area, nombre_area FROM areas";
$result_areas = $conn->query($sql_areas);

// Verificar si se ha proporcionado un ID de activo
if (!isset($_GET['id_activo']) || empty($_GET['id_activo'])) {
    die("ID de activo no especificado.");
}

$id_activo = intval($_GET['id_activo']);

// Obtener información actual del activo
$sql_activo = "SELECT id_area FROM activos WHERE id_activo = ?";
$stmt = $conn->prepare($sql_activo);
$stmt->bind_param("i", $id_activo);
$stmt->execute();
$result_activo = $stmt->get_result();
$activo = $result_activo->fetch_assoc();

if (!$activo) {
    die("Activo no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/ingresardatos.css">
    <title>Mantenisoft - Actualizar Área del Activo</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <div class="info-box">
        <h2>📌 Instrucciones</h2>
        <ul>
            <li><strong>📍 Área:</strong> Selecciona el área actualizada donde se encuentra el activo.</li>
            <li><strong>📌 Subárea:</strong> (Opcional) Especifica un área más detallada.</li>
        </ul>
    </div>

    <form action="back/procesar_actualizacion_area.php" method="post">
        <input type="hidden" name="id_activo" value="<?= $id_activo ?>">

        <label for="area">Seleccionar nueva área:</label>
        <select name="area" id="area" required>
            <option value="">Seleccione un área</option>
            <?php while ($row = $result_areas->fetch_assoc()) { ?>
                <option value="<?= $row['id_area'] ?>" <?= ($row['id_area'] == $activo['id_area']) ? 'selected' : '' ?>>
                    <?= $row['nombre_area'] ?>
                </option>
            <?php } ?>
        </select>

        <label for="subarea">Seleccionar subárea (opcional):</label>
        <select name="subarea" id="subarea">
            <option value="">(Opcional) Seleccione una subárea</option>
        </select>

        <button type="submit">Actualizar Área</button>
        <a href="../../inicioactivos.php" class="back-button">Volver al menú</a>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#area').change(function() {
        var area_id = $(this).val();
        
        $.ajax({
            url: 'back/obtener_subareas.php',
            type: 'POST',
            data: { area_id: area_id },
            success: function(response) {
                $('#subarea').html(response);
            }
        });
    });
});
</script>
</body>
</html>
<?php $conn->close(); ?>