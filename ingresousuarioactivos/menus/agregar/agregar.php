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
    <title>mantenisoft</title>
</head>
<body>
    <form action="back/subir_imagen.php" method="post" enctype="multipart/form-data">
        <input type="text" name="nombre" placeholder="Nombre del Dispositivo" required oninput="this.value = this.value.toUpperCase()">
        
        <select name="tipo" required>
            <option value="">Seleccione un tipo</option>
            <option value="computador">computador</option>
            <option value="telefono">telefono</option>
            <option value="impresora">impresora</option>
            <option value="portatil">portatil</option>
        </select>

        <input type="text" name="estado" value="activo" readonly>

        <select name="area" required>
            <option value="">Seleccione un área</option>
            <?php while ($row = $result_areas->fetch_assoc()) { ?>
                <option value="<?= $row['id_area'] ?>"><?= $row['nombre_area'] ?></option>
            <?php } ?>
        </select>

        <select name="subarea">
            <option value="">(Opcional) Seleccione una subárea</option>
            <?php while ($row = $result_subareas->fetch_assoc()) { ?>
                <option value="<?= $row['id_areas_especificas'] ?>"><?= $row['area_especifica_nombre'] ?></option>
            <?php } ?>
        </select>

        <input type="text" name="NPlaca" placeholder="Número de la placa" required oninput="this.value = this.value.toUpperCase()">
        
        <input type="file" name="imagen" accept="image/*" required>
        
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
<?php $conn->close(); ?>
