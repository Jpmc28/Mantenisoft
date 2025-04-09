<?php
// Datos de conexión
$host = '127.0.0.1';
$db = 'mantenisoft';
$user = 'root';
$pass = '';

// Conectar a MySQL
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

// Archivo CSV
$csvFile = __DIR__ . '/computadores.csv';

if (!file_exists($csvFile)) {
    die('No se encontró el archivo CSV.');
}

// Contadores
$insertados = 0;
$errores = 0;
$incompletos = 0;

$erroresDetalles = []; // Guardar detalles de errores

// Función para validar existencia de ID en tabla
function existeId($conn, $tabla, $campo, $valor) {
    $sql = "SELECT COUNT(*) FROM `$tabla` WHERE `$campo` = $valor";
    $resultado = $conn->query($sql);
    if ($resultado) {
        $fila = $resultado->fetch_row();
        return $fila[0] > 0;
    }
    return false;
}

// Leer el CSV
if (($handle = fopen($csvFile, 'r')) !== false) {
    while (($data = fgetcsv($handle, 1000, ";", '"', "\\")) !== false) {
        if (count(array_filter($data)) == 0) {
            continue; // Ignorar líneas vacías
        }

        if (count($data) < 7) {
            $incompletos++;
            continue; // Ignorar registros incompletos
        }

        $nombre = $conn->real_escape_string(trim($data[0]));
        $tipo = $conn->real_escape_string(trim($data[1]));
        $estado = $conn->real_escape_string(trim($data[2]));
        $id_area = (int) $data[3];
        $fecha_ingreso = $conn->real_escape_string(trim($data[4]));
        $id_usuario = (int) $data[5];
        $NPlaca = $conn->real_escape_string(trim($data[6]));
        $id_areas_especificas = isset($data[7]) && $data[7] !== '' ? (int)$data[7] : null;

        // Validar existencia de id_area
        if (!existeId($conn, 'areas', 'id_area', $id_area)) {
            $errores++;
            $erroresDetalles[] = [
                'nombre' => $nombre,
                'NPlaca' => $NPlaca,
                'error' => "No existe id_area $id_area"
            ];
            continue;
        }

        // Crear el SQL dinámicamente
        if (is_null($id_areas_especificas)) {
            $sql = "INSERT INTO activos (nombre, tipo, estado, id_area, fecha_ingreso, id_usuario, NPlaca)
                    VALUES ('$nombre', '$tipo', '$estado', $id_area, '$fecha_ingreso', $id_usuario, '$NPlaca')";
        } else {
            $sql = "INSERT INTO activos (nombre, tipo, estado, id_area, numero_serie,fecha_ingreso, id_usuario, NPlaca, id_areas_especificas)
                    VALUES ('$nombre', '$tipo', '$estado', $id_area, '$nserie' ,'$fecha_ingreso', $id_usuario, '$NPlaca', $id_areas_especificas)";
        }

        if ($conn->query($sql)) {
            $insertados++;
        } else {
            $errores++;
            $erroresDetalles[] = [
                'nombre' => $nombre,
                'NPlaca' => $NPlaca,
                'error' => $conn->error
            ];
        }
    }
    fclose($handle);
} else {
    die('No se pudo abrir el archivo CSV.');
}

// Mostrar resultados
echo "<h2>Resultado de la carga masiva</h2>";
echo "<p>Insertados exitosamente: <strong>$insertados</strong></p>";
echo "<p>Errores de inserción: <strong>$errores</strong></p>";
echo "<p>Registros incompletos ignorados: <strong>$incompletos</strong></p>";

if ($errores > 0) {
    echo "<h3>Detalle de errores:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Nombre</th><th>Placa</th><th>Motivo del Error</th></tr>";
    foreach ($erroresDetalles as $detalle) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($detalle['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($detalle['NPlaca']) . "</td>";
        echo "<td>" . htmlspecialchars($detalle['error']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Cerrar conexión
$conn->close();
?>
