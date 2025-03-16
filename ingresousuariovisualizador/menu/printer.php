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

$query = "SELECT c.id_activo, c.tipo_cambio, c.fecha_cambio, 
                 p.id_piso, p.nombre_piso, a.nombre_area, act.nombre AS impresora
          FROM cambios_impresoras c
          JOIN activos act ON c.id_activo = act.id_activo
          JOIN areas a ON act.id_area = a.id_area
          JOIN pisos p ON a.id_piso = p.id_piso
          WHERE c.fecha_cambio BETWEEN ? AND ?
          ORDER BY p.id_piso, a.nombre_area, c.fecha_cambio";

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
    <title>Reporte de Impresoras</title>
    <link rel="stylesheet" href="css/stilosp.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Reporte de Cambios en Impresoras</h2>
        
        <!-- Formulario de filtros -->
        <form method="GET" class="mb-6 flex space-x-4">
            <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="border p-2 rounded">
            <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="border p-2 rounded">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filtrar</button>
        </form>

        <!-- Contenedor de Gráficos -->
        <div class="grid grid-cols-2 gap-6">
            <?php 
            $max_cambios = 0;

            // Encuentra el máximo de cambios para normalizar escalas
            foreach ($data as $cambios) {
                $max_cambios = max($max_cambios, count($cambios));
            }

            // Generar gráficos
            foreach ($data as $piso => $cambios): ?>
                <div class="p-4 bg-gray-50 rounded shadow">
                    <h3 class="text-lg font-semibold mb-2">Piso <?php echo $cambios[0]['nombre_piso']; ?></h3>
                    <div id="chart<?php echo $piso; ?>"></div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            var cambios = <?php echo json_encode($cambios); ?>;
                            var categorias = [];
                            var valores = [];
                            var tiposCambios = {};

                            cambios.forEach(function(item) {
                                var label = item.impresora + " (" + item.nombre_area + ")";
                                var index = categorias.indexOf(label);

                                if (index === -1) {
                                    categorias.push(label);
                                    valores.push(1);
                                    tiposCambios[label] = [item.tipo_cambio]; // Guardamos el tipo de cambio
                                } else {
                                    valores[index]++;
                                    tiposCambios[label].push(item.tipo_cambio); // Agregamos más tipos de cambio
                                }
                            });

                            var options = {
                                chart: { type: 'bar', height: 350 },
                                series: [{ name: 'Cantidad de cambios', data: valores }],
                                xaxis: { categories: categorias },
                                yaxis: { max: <?php echo $max_cambios; ?> },
                                tooltip: {
                                    y: {
                                        formatter: function(value, { dataPointIndex }) {
                                            var categoria = categorias[dataPointIndex];
                                            var cambiosUnicos = [...new Set(tiposCambios[categoria])]; // Evita duplicados
                                            return cambiosUnicos.join(", "); // Muestra los tipos de cambios
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
