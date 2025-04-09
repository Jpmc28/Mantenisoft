<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="website icon" href="img/GtuzsKu2ryrS5m0Z-removebg-preview1.png">
    <title>Uztech</title>
</head>
<body>
    <div id="seccion_video">
        <img src="img/logo_on_board_2-removebg-preview.png" alt="" id="logo_personal">
        <section class="videoDeSoftware">
            <video autoplay muted loop preload="auto">
                <source src="img/Clínica Nueva El Lago - Colaboradores NEPS (1080p, h264, youtube).mp4" type="video/mp4">
                Tu navegador no soporta la etiqueta de video.
            </video>
        </section>
    </div>
    <form action="phpbacklogeoandresgister/login1.php" method="POST">
        <section class="Ingresar">
                <img src="img/GtuzsKu2ryrS5m0Z-removebg-preview.png" alt="" id="logo_clinica">
                <input type="email" name="email" id="email" placeholder="Correo Institucional" required>
                <br>
                <input type="password" name="password" id="password" placeholder="Contraseña" required>
                <br>
                <button type="submit" id="ingresar">Ingresar</button>
                <br>
                <a href="#" id="OMC">Olvide mi contraseña</a>
                <br>
                <a href="phpbacklogeoandresgister/registrarse.php">Registrarme</a>
        </section>
    </form>
    <script>
    document.getElementById("OMC").addEventListener("click", function(event) {
        event.preventDefault(); // Evita que el enlace recargue la página
        alert("Si olvido su contraseña o necesita restablecerla comuniquese con el área de sistemas para que le indiquen como restablecer su contraseña tel: 3222501850 extensión: 1302");
    });
</script>

</body>
</html>