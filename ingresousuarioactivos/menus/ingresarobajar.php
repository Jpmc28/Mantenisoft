<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipo_usuario'] != 'activos' && $_SESSION['tipo_usuario'] != 'super_usuario')) {
  header("Location: ../../index.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/ingresarobajar.css">
    <title>Uztech</title>
</head>
<body>
    <div id="seccion_eleccion">
        <h1>¿Qué requieres realizar?</h1>

        <div class="botones">
            <div class="menu-icon" onclick="mostrarAdvertencia('baja')">
                <h2>Dar equipo de baja o moverlo</h2>
            </div>

            <div class="menu-icon" onclick="mostrarAdvertencia('agregar')">
                <h2>Agregar equipo</h2>
            </div>
        </div>
    </div>

    <!-- Advertencia -->
    <div id="advertencia" class="modal">
        <div class="modal-content">
            <h1 class="advertencia">¡Advertencia!</h1>
            <p id="mensaje"></p>
            <a id="aceptar" href="#">Aceptar</a>
        </div>
    </div>

    <script>
        function mostrarAdvertencia(tipo) {
            let mensaje = tipo === 'baja'
                ? "Al dar aceptar entiende que dará de baja un activo. Quedará guardado en la base de datos por 2 dias.<br>Al concluir los dias, el activo será eliminado."
                : "Al dar aceptar entiende que ingresará un activo. Esto se vera reflejado en la base de datos y quedara el registro de a que hora y quien lo ingreso.";

            document.getElementById("mensaje").innerHTML = mensaje;
            document.getElementById("aceptar").href = tipo === 'baja' ? "baja/baja1.php" : "agregar/agregar.php";
            document.getElementById("advertencia").style.display = "flex";
        }

        // Cerrar advertencia al hacer clic fuera de ella
        document.getElementById("advertencia").addEventListener("click", function(event) {
            if (event.target === this) {
                this.style.display = "none";
            }
        });
    </script>
</body>
</html>