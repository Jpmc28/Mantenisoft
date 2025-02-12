<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'activos') {
    header("Location: ../index.php");
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

// Obtener subáreas
$sql_subareas = "SELECT id_area_especifica, area_especifica_nombre FROM areas_especificas";
$result_subareas = $conn->query($sql_subareas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/ingresardatos.css">
    <link rel="website icon" href="img/GtuzsKu2ryrS5m0Z-removebg-preview1.png">
    <title>Mantenisoft - Ingreso de Activos</title>
</head>
<body>
<div class="container">
        <div class="info-box">
            <h2>📌 Instrucciones</h2>
            <ul>
                <li><strong>📌 Nombre del Dispositivo:</strong> Ingresa el nombre exacto del activo.</li>
                <li><strong>🖥️ Tipo:</strong> Selecciona si es computador, teléfono, impresora o portátil.</li>
                <li><strong>📍 Área:</strong> Indica en qué área está ubicado el dispositivo.</li>
                <li><strong>📌 Subárea:</strong> (Opcional) Especifica un área más detallada.</li>
                <li><strong>🔢 Número de Placa:</strong> Introduce el número de identificación del activo.</li>
                <li><strong>🖼️ Imagen:</strong> Sube una imagen del activo.</li>
            </ul>
        </div>
        
        <form action="back/subir_imagen.php" method="post" enctype="multipart/form-data" id="uploadForm">
            <input type="text" name="nombre" placeholder="Nombre del Dispositivo" required>
            
            <select name="tipo" required>
                <option value="">Seleccione un tipo</option>
                <option value="computador">Computador</option>
                <option value="telefono">Teléfono</option>
                <option value="impresora">Impresora</option>
                <option value="portatil">Portátil</option>
            </select>

            <select name="area" required>
                <option value="">Seleccione un área</option>
                <?php while ($row = $result_areas->fetch_assoc()) { ?>
                    <option value="<?= $row['id_area'] ?>"><?= $row['nombre_area'] ?></option>
                <?php } ?>
            </select>

            <select name="subarea">
                <option value="">(Opcional) Seleccione una subárea</option>
                <?php while ($row = $result_subareas->fetch_assoc()) { ?>
                    <option value="<?= $row['id_area_especifica'] ?>"><?= $row['area_especifica_nombre'] ?></option>
                <?php } ?>
            </select>

            <input type="text" name="NPlaca" placeholder="Número de la placa" required>
            <input type="file" name="imagen" accept="image/*" required>

            <button type="submit" id="submitButton">Guardar</button>
            <a href="../../inicioactivos.php" class="back-button">Volver al menú</a>
            <div id="statusMessage"></div>
        </form>
    </div>
<script src="js/script.js"></script>
</body>
</html>
<?php $conn->close(); ?>
