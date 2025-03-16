<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipo_usuario'] != 'visualizador' && $_SESSION['tipo_usuario'] != 'super_usuario')) {
  header("Location: ../index.php");
  exit();
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mantenisoft';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$start_date = $_GET['start_date'] ?? '2025-01-01';
$end_date = $_GET['end_date'] ?? date('Y-m-d');

$query = "SELECT m.id_activo, m.tipo_cambio, m.fecha_cambio, 
                 p.id_piso, p.nombre_piso, a.nombre_area, act.nombre AS telefono
          FROM mantenimientos_telefonos m
          JOIN activos act ON m.id_activo = act.id_activo
          JOIN areas a ON act.id_area = a.id_area
          JOIN pisos p ON a.id_piso = p.id_piso
          WHERE m.fecha_cambio BETWEEN ? AND ?
          ORDER BY p.id_piso, a.nombre_area, m.fecha_cambio";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $piso = $row['id_piso'];
    if (!isset($data[$piso])) {
        $data[$piso] = [];
    }
    $data[$piso][] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Teléfonos</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/stilost.css">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Reporte de Daños en Teléfonos</h2>
        
        <form method="GET" class="mb-6 flex space-x-4">
            <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="border p-2 rounded">
            <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="border p-2 rounded">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filtrar</button>
        </form>

        <div class="grid grid-cols-2 gap-6">
            <?php foreach ($data as $piso => $mantenimientos): ?>
                <div class="p-4 bg-gray-50 rounded shadow">
                    <h3 class="text-lg font-semibold mb-2">Piso <?php echo $mantenimientos[0]['nombre_piso']; ?></h3>
                    <div id="chart<?php echo $piso; ?>"></div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            var mantenimientos = <?php echo json_encode($mantenimientos); ?>;
                            var categorias = [];
                            var series = [];

                            mantenimientos.forEach(function(item) {
                                var label = item.telefono + " (" + item.nombre_area + ")";
                                if (!categorias.includes(label)) {
                                    categorias.push(label);
                                    series.push({ x: label, y: 1, tipo: item.tipo_cambio });
                                } else {
                                    var index = categorias.indexOf(label);
                                    series[index].y++;
                                    series[index].tipo += ", " + item.tipo_cambio;
                                }
                            });

                            var options = {
                                chart: { type: 'bar', height: 350 },
                                series: [{ name: 'Mantenimientos', data: series }],
                                xaxis: { categories: categorias },
                                tooltip: {
                                    y: {
                                        formatter: function(value, { dataPointIndex }) {
                                            return series[dataPointIndex].tipo;
                                        }
                                    }
                                }
                            };
                            var chart = new ApexCharts(document.querySelector("#chart<?php echo $piso; ?>"), options);
                            chart.render();
                        });
                    </script>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>