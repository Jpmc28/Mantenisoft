<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../../../index.php");
    exit();
}

$id_activo = $_GET['id_activo'] ?? null;
if (!$id_activo) {
    die("Error: ID del activo no proporcionado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Mantenimiento de Teléfonos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Mantenimiento de Teléfono</h2>
        <form action="procesar_mantenimiento.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_activo" value="<?= htmlspecialchars($id_activo) ?>">
            
            <label for="tipo_cambio">Tipo de Cambio:</label>
            <select name="tipo_cambio" required>
                <option value="cable energía">Cable Energía</option>
                <option value="cable voz">Cable Voz</option>
                <option value="bocina">Bocina</option>
            </select>

            <label for="imagen_mantenimiento">Subir Imagen:</label>
            <input type="file" name="imagen_mantenimiento" accept="image/*">

            <button type="submit">Registrar Cambio</button>
        </form>
    </div>
</body>
</html>
