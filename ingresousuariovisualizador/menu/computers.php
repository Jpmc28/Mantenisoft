<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../index.php");
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

// Obtener filtros de fecha
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Filtro opcional por área
$area = isset($_GET['area']) ? (int) $_GET['area'] : null;

// Consulta SQL
$sql = "SELECT p.nombre_piso, COUNT(m.id_mantenimiento) as total 
        FROM mantenimientos m
        JOIN activos ac ON m.id_activo = ac.id_activo
        JOIN areas a ON ac.id_area = a.id_area
        JOIN pisos p ON a.id_piso = p.id_piso
        WHERE m.fecha_mantenimiento BETWEEN ? AND ?";

$params = [$start_date, $end_date];
$types = "ss";

if ($area) {
    $sql .= " AND a.id_area = ?";
    $params[] = $area;
    $types .= "i";
}
$sql .= " GROUP BY p.nombre_piso";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Mantenimientos</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Reporte de Mantenimientos por Piso</h2>
    <form method="GET">
        Fecha Inicio: <input type="date" name="start_date" value="<?= $start_date ?>" required>
        Fecha Fin: <input type="date" name="end_date" value="<?= $end_date ?>" required>
        <button type="submit">Filtrar</button>
    </form>
    <canvas id="miGrafico"></canvas>
    
    <table>
        <thead>
            <tr>
                <th>Piso</th>
                <th>Mantenimientos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nombre_piso']) ?></td>
                    <td><?= htmlspecialchars($row['total']) ?></td>
                    <td><button onclick="obtenerDetallesMantenimiento('<?= $row['nombre_piso'] ?>')">Ver Detalles</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div id="detalleModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Detalles del Mantenimiento</h3>
            <div id="detalleContenido"></div>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById("miGrafico").getContext("2d");
            const data = <?= json_encode($data) ?>;
            const labels = data.map(item => item.nombre_piso);
            const values = data.map(item => item.total);
            
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Mantenimientos",
                        data: values,
                        backgroundColor: "rgba(54, 162, 235, 0.5)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    },
                    onClick: function (event, elements) {
                        if (elements.length > 0) {
                            let index = elements[0].index;
                            let pisoSeleccionado = this.data.labels[index];
                            obtenerDetallesMantenimiento(pisoSeleccionado);
                        }
                    }
                }
            });
        });
        
        function obtenerDetallesMantenimiento(piso) {
    fetch(`detalles.php?piso=${encodeURIComponent(piso)}`)
        .then(response => response.json())
        .then(data => {
            let contenido = "";

            if (data.error) {
                contenido = `<p>${data.error}</p>`;
            } else if (data.length === 0) {
                contenido = "<p>No hay mantenimientos completados para este piso.</p>";
            } else {
                contenido = "<div class='modal-content-scroll'>";
                data.forEach(mantenimiento => {
                    contenido += `
                        <div class="mantenimiento-item">
                            <p><strong>Equipo:</strong> ${mantenimiento.NPlaca}</p>
                            <p><strong>Fecha:</strong> ${mantenimiento.fecha_mantenimiento}</p>
                            <p><strong>Técnico:</strong> ${mantenimiento.tecnico}</p>
                            <p><strong>Responsable:</strong> ${mantenimiento.nombre_responsable} (${mantenimiento.cargo_responsable})</p>
                            <p><strong>Tareas Realizadas:</strong></p>
                            <ul>
                    `;

                    // Mostrar tareas completadas en Software
                    if (mantenimiento.descripcion.software.length > 0) {
                        contenido += `<li><strong>Software:</strong></li>`;
                        mantenimiento.descripcion.software.forEach(tarea => {
                            contenido += `<li>- ${tarea.replace(/_/g, " ")}</li>`;
                        });
                    }

                    // Mostrar tareas completadas en Hardware
                    if (mantenimiento.descripcion.hardware.length > 0) {
                        contenido += `<li><strong>Hardware:</strong></li>`;
                        mantenimiento.descripcion.hardware.forEach(tarea => {
                            contenido += `<li>- ${tarea.replace(/_/g, " ")}</li>`;
                        });
                    }

                    contenido += `
                            </ul>
                            <p><strong>Firma Responsable Del Activo:</strong></p>
                            <img src="${mantenimiento.firma_tecnico}" alt="Firma del técnico" class="firma-img">
                            <hr>
                        </div>
                    `;
                });
                contenido += "</div>";
            }

            document.getElementById("detalleContenido").innerHTML = contenido;
            document.getElementById("detalleModal").style.display = "flex";
        })
        .catch(error => {
            console.error("Error en fetch:", error);
            document.getElementById("detalleContenido").innerHTML = `<p>Error al obtener los datos</p>`;
        });
}

// Cerrar modal al hacer clic en la "X"
document.querySelector(".close").addEventListener("click", function () {
    document.getElementById("detalleModal").style.display = "none";
});

document.querySelector(".close").addEventListener("click", function () {
    document.getElementById("detalleModal").style.display = "none";
});

        
        document.querySelector(".close").addEventListener("click", function () {
            document.getElementById("detalleModal").style.display = "none";
        });
    </script>
</body>
</html>