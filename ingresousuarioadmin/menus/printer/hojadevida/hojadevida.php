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

// Obtener el ID del activo desde la URL
if (!isset($_GET['id_activo']) || empty($_GET['id_activo'])) {
    die("<p style='color: red;'>Error: No se ha especificado un equipo a consultar.</p>");
}
$id_activo = intval($_GET['id_activo']);

// Verificar que el ID del activo se recibe correctamente
// echo "<p>ID del activo recibido: $id_activo</p>";

// Consulta para obtener la información del equipo
$sql = "SELECT a.nombre, a.tipo, a.estado, a.NPlaca, ar.nombre_area, 
               es.procesador, es.ram, es.almacenamiento, es.sistema_operativo, es.software_instalado, es.nombre_dominio, 
               ars.area_especifica_nombre
        FROM activos a
        JOIN areas ar ON a.id_area = ar.id_area
        LEFT JOIN especificaciones es ON a.id_activo = es.id_activo
        LEFT JOIN areas_especificas ars ON a.id_areas_especificas = ars.id_area_especifica
        WHERE a.id_activo = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_activo);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si hay resultados
if ($resultado->num_rows == 0) {
    die("<p style='color: red;'>No se encontró información para el equipo seleccionado.</p>");
}
$equipo = $resultado->fetch_assoc();

// Consulta para obtener los mantenimientos del equipo
$sql_mantenimientos = "SELECT m.id_cambio, m.fecha_cambio, u.nombre AS nombre_usuario, m.tipo_cambio
                        FROM cambios_impresoras m
                        JOIN usuarios u ON m.id_usuario = u.id_usuario
                        WHERE m.id_activo = ?
                        ORDER BY m.fecha_cambio DESC;";


$stmt_mantenimientos = $conn->prepare($sql_mantenimientos);
$stmt_mantenimientos->bind_param("i", $id_activo);
$stmt_mantenimientos->execute();
$resultado_mantenimientos = $stmt_mantenimientos->get_result();

// Guardar los mantenimientos en un array
$mantenimientos = [];
while ($fila = $resultado_mantenimientos->fetch_assoc()) {
    $mantenimientos[] = $fila;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/hojadevida.css">
    <title>Detalles del Computador</title>
</head>
<body>
<div class="contenedor">
    <div class="detalle-equipo">
        <h2>Detalles del telefono</h2>
        <div class="contenido">
            <div class="info-equipo">
                <h3><?php echo htmlspecialchars($equipo['nombre']); ?></h3>
                <p><strong>Tipo:</strong> <?php echo htmlspecialchars($equipo['tipo']); ?></p>
                <p><strong>Estado:</strong> <?php echo htmlspecialchars($equipo['estado']); ?></p>
                <p><strong>Número de Placa:</strong> <?php echo htmlspecialchars($equipo['NPlaca']); ?></p>
                <p><strong>Área:</strong> <?php echo htmlspecialchars($equipo['nombre_area']); ?></p>
                <p><strong>Área Específica:</strong> <?php echo htmlspecialchars($equipo['area_especifica_nombre']); ?></p>
            </div>
            
            <!-- Imagen del equipo -->
            <div class="imagen-container">
                <div class="imagen-wrapper">
                    <img src="mostrar_imagen.php?id_activo=<?php echo $id_activo; ?>" alt="Imagen del equipo" class="imagen-equipo">
                    <div class="overlay" onclick="document.getElementById('inputImagen').click();">Actualizar Imagen</div>
                </div>
                <form id="formActualizarImagen" action="actualizar_imagen.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_activo" value="<?php echo $id_activo; ?>">
                    <input type="file" name="imagen" id="inputImagen" accept="image/*" style="display: none;" onchange="document.getElementById('formActualizarImagen').submit();">
                </form>
            </div>
        </div>

        <!-- Sección de Mantenimientos -->
        <h3>Historial de suministros cambiados</h3>
            <div class="historial-mantenimiento">
                <?php if (!empty($mantenimientos)): ?>
                    <ul>
                        <?php foreach ($mantenimientos as $mantenimiento): ?>
                            <li>
                                <span class="fecha"><?php echo htmlspecialchars($mantenimiento['fecha_cambio']); ?></span>
                                <span class="usuario"><?php echo htmlspecialchars($mantenimiento['nombre_usuario']); ?></span>
                                <span class="estado">(<?php echo htmlspecialchars($mantenimiento['tipo_cambio']); ?>)</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No hay cambios registrados para este equipo.</p>
                <?php endif; ?>
            </div>
        <!-- Botones -->
        <div class="botones">
            <form action="agregar_mantenimiento.php" method="GET">
                <input type="hidden" name="id_activo" value="<?php echo $id_activo; ?>">
                <button type="submit" class="btn mantenimiento">Cambio de suministros</button>
            </form>
        </div>
    </div>
</div>


<script>
    document.getElementById("inputImagen").addEventListener("change", function () {
        document.getElementById("formActualizarImagen").submit();
    });
</script>
</body>
</html>