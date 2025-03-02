<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'activos') {
    header("Location: ../index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estiloInicio.css">
    <link rel="website icon" href="img/GtuzsKu2ryrS5m0Z-removebg-preview1.png">
    <script>
      document.addEventListener("DOMContentLoaded", function () {
          fetch("obtener_activos.php")
              .then(response => response.json())
              .then(data => {
                  if (data.error) {
                      console.error("Error:", data.error);
                      return;
                  }
                  const lista = document.getElementById("listaActivos");
                  lista.innerHTML = "";
                  data.forEach(activo => {
                      const item = document.createElement("li");
                      item.innerHTML = `<strong>${activo.nombre}</strong> - Placa: ${activo.NPlaca}`;
                      lista.appendChild(item);
                  });
              })
              .catch(error => console.error("Error al obtener los activos:", error));
      });
      </script>
    <title>InicioAdmin</title>
</head>
<body>
  <img src="img/GtuzsKu2ryrS5m0Z-removebg-preview.png" alt="" id="logocli">
    <section id="personalydatots">
        <section id="tablaPeronal">
            <img src="img/informacionbasica.png" alt="" id="cabeza">
            <h3>Nombre: <?php echo htmlspecialchars($_SESSION['nombre']); ?></h3>
            <h3>CC:  <?php echo htmlspecialchars($_SESSION['Cedula']); ?></h3>
            <h3>Cargo: <?php echo htmlspecialchars($_SESSION['Rol']); ?></h3>
            <h3>Correo:  <?php echo htmlspecialchars($_SESSION['correo']); ?></h3>
            <a href="actualizardatos.php"><button id="boton_actualizar">Actualizar</button></a>
        </section>
        <section id="tablaServicios">
            <section id="servicios">
                <p>Activos Insertados</p>
            </section>
            <section id="serviciosRealizados">
                <div id="contenedorActivos">
                    <ul id="listaActivos"></ul>
                </div>
            </section>
        </section>
    </section>
    <div class="menu-container">
    <div class="menu-icon">
      <img src="img/imagen_principal-removebg-preview.png" alt="Menú">
    </div>
    <div class="dropdown-menu">
      <a href="menus/consultarActivos.php"><img src="img/imagen_consulta1.png" alt="Consulte los activos" title="Consulte los activos" id="foto2"></a>
      <a href="menus/ingresarobajar.php"><img src="img/ingresoactivos1.jpg" alt="Insertar o dar de baja activos" title="Insertar o dar de baja activos" id="foto4"></a>
    </div>
  </div>
</body>
</html>