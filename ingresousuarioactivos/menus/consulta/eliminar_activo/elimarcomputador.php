<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipo_usuario'] != 'activos' && $_SESSION['tipo_usuario'] != 'super_usuario')) {
  header("Location: ../../../../../index.php");
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
if (!isset($_GET['id_activo'])) {
    die("<p style='color: red;'>Error: No se ha especificado un equipo a eliminar.</p>");
}
$id_activo = intval($_GET['id_activo']);

// Consulta para obtener la información del equipo y periféricos
$sql = "SELECT a.nombre, a.tipo, a.estado, a.NPlaca, ar.nombre_area, 
               es.procesador, es.ram, es.almacenamiento, es.sistema_operativo, es.software_instalado, es.nombre_dominio, 
               ars.area_especifica_nombre, per.placa, per.tipo
        FROM activos a
        JOIN areas ar ON a.id_area = ar.id_area
        LEFT JOIN especificaciones es ON a.id_activo = es.id_activo
        LEFT JOIN areas_especificas ars ON a.id_areas_especificas = ars.id_area_especifica
        LEFT JOIN perifericos per ON a.id_activo = per.id_activo
        WHERE a.id_activo = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_activo);
$stmt->execute();
$resultado = $stmt->get_result();

$monitor = "";
$teclado = "";
$mouse = "";
$equipo = null;

// Leer los resultados
while ($fila = $resultado->fetch_assoc()) {
    if (!$equipo) {
        $equipo = $fila; // Guarda la información del equipo en la primera iteración
    }
    
    // Asignar cada periférico a su respectivo apartado
    if ($fila['tipo'] === 'monitor') {
        $monitor = htmlspecialchars($fila['placa']);
    } elseif ($fila['tipo'] === 'teclado') {
        $teclado = htmlspecialchars($fila['placa']);
    } elseif ($fila['tipo'] === 'mouse') {
        $mouse = htmlspecialchars($fila['placa']);
    }
}

// Verificar si se encontró información del equipo
if (!$equipo) {
    die("<p style='color: red;'>No se encontró información para el equipo seleccionado.</p>");
}

$stmt->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/eliminacioncomputador.css">
    <title>Eliminar Computador</title>
</head>
<body>
    <div class="contenedor">
        <div class="detalle-equipo">
            <h2>Detalles del Computador</h2>
            <div class="contenido">
                <div class="info-equipo">
                    <h3><?php echo htmlspecialchars($equipo['nombre']); ?></h3>
                    <p><strong>Tipo:</strong> <?php echo htmlspecialchars($equipo['tipo']); ?></p>
                    <p><strong>Estado:</strong> <?php echo htmlspecialchars($equipo['estado']); ?></p>
                    <p><strong>Número de Placa:</strong> <?php echo htmlspecialchars($equipo['NPlaca']); ?></p>
                    <p><strong>Área:</strong> <?php echo htmlspecialchars($equipo['nombre_area']); ?></p>
                    <p><strong>Area Especifica:</strong> <?php echo htmlspecialchars($equipo['area_especifica_nombre'])?: "No registrado"; ?></p>
                    <p><strong>Monitor:</strong> <?php echo $monitor ?: "No registrado"; ?></p>
                    <p><strong>Teclado:</strong> <?php echo $teclado ?: "No registrado"; ?></p>
                    <p><strong>Mouse:</strong> <?php echo $mouse ?: "No registrado"; ?></p>
                </div>
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
            <div class="botones">
                <form action="agregar.php" method="GET">
                    <input type="hidden" name="id_activo" value="<?php echo $id_activo; ?>">
                    <button type="submit" class="btn actualizar">Agregar Perifericos</button>
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
