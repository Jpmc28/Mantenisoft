<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipo_usuario'] != 'activos' && $_SESSION['tipo_usuario'] != 'super_usuario')) {
  header("Location: ../../../index.php");
  exit();
}

$host = "localhost";
$user = "root";
$password = "";
$database = "mantenisoft";
$conn = new mysqli($host, $user, $password, $database);

// Obtener áreas
$sql_areas = "SELECT id_area, nombre_area FROM areas";
$result_areas = $conn->query($sql_areas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/ingresardatos.css">
    <title>Mantenisoft - Ingreso de Activos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
        <div class="info-box">
            <h2>📌 Instrucciones</h2>
            <ul>
                <li><strong>📌 Nombre del Dispositivo:</strong> Ingresa el nombre exacto del activo (marca o modelo).</li>
                <li><strong>🖥️ Tipo:</strong> Selecciona si es computador, teléfono, impresora o portátil.</li>
                <li><strong>📍 Área:</strong> Indica en qué área está ubicado el dispositivo.</li>
                <li><strong>📌 Subárea:</strong> (Opcional) Especifica un área más detallada.</li>
                <li><strong>🔢 Número de Placa:</strong> Introduce el número de identificación del activo (LC000000).</li>
                <li><strong>🖼️ Imagen:</strong> Sube una imagen del activo.</li>
            </ul>
        </div>
        
        <form action="back/subir_imagen.php" method="post" enctype="multipart/form-data" id="uploadForm">
            <div id="texto"><H2>Aqui podra ingresar activos</H2></div>
            <input type="text" name="nombre" placeholder="Nombre del Dispositivo" required>
            
            <select name="tipo" required>
                <option value="">Seleccione un tipo</option>
                <option value="computador">Computador</option>
                <option value="telefono">Teléfono</option>
                <option value="impresora">Impresora</option>
                <option value="portatil">Portátil</option>
            </select>

            <label for="area"></label>
            <select name="area" id="area" required>
                <option value="">Seleccione un área</option>
                <?php while ($row = $result_areas->fetch_assoc()) { ?>
                    <option value="<?= $row['id_area'] ?>"><?= $row['nombre_area'] ?></option>
                <?php } ?>
            </select>

            <label for="subarea"></label>
            <select name="subarea" id="subarea">
                <option value="">(Opcional) Seleccione una subárea</option>
            </select>

            <input type="text" name="NPlaca" placeholder="Número de la placa" required>
            <input id="imagen" type="file" name="imagen" accept="image/*" required>

            <button type="submit" id="submitButton">Guardar</button>
            <a href="../../inicioactivos.php" class="back-button">Volver al menú</a>
            <div id="statusMessage"></div>
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
<script src="js/script.js"></script>
</body>
</html>
<?php $conn->close(); ?>
