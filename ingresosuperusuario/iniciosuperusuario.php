<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'super_usuario') {
    header("Location: ../index.php");
    exit();
}

// Conexión a la base de datos
$host = 'localhost'; 
$user = 'root';      
$password = '';      
$database = 'mantenisoft';  

$conn = new mysqli($host, $user, $password, $database);

// Obtener usuarios
$usuarios = $conn->query("SELECT * FROM usuarios");

// Agregar usuario
if (isset($_POST['agregar_usuario'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $cedula = $_POST['cedula'];
    $rol = $_POST['rol'];
    $tipo_usuario = $_POST['tipo_usuario'];
    
    $conn->query("INSERT INTO usuarios (nombre, correo, cedula, rol, tipo_usuario, estado) VALUES ('$nombre', '$correo', '$cedula', '$rol', '$tipo_usuario', 'activo')");
    header("Location: iniciosuperusuario.php");
}

// Restablecer contraseña
if (isset($_GET['reset_password'])) {
    $id = $_GET['reset_password'];
    $conn->query("UPDATE usuarios SET contraseña = NULL WHERE id_usuario = $id");
    
    $conn->query("INSERT INTO historial (id_usuario, accion) VALUES ($id, 'Restablecimiento de contraseña')");
    header("Location: iniciosuperusuario.php");
}

// Cambiar estado del usuario (activar/inactivar)
if (isset($_GET['cambiar_estado']) && isset($_GET['nuevo_estado'])) {
    $id_usuario = $_GET['cambiar_estado'];
    $nuevo_estado = $_GET['nuevo_estado'];
    
    // Actualizar estado en la base de datos
    $conn->query("UPDATE usuarios SET estado = '$nuevo_estado' WHERE id_usuario = $id_usuario");
    
    // Registrar en historial
    $accion = ($nuevo_estado == 'activo') ? 'usuario activado' : 'usuario inactivo';
    $conn->query("INSERT INTO historial (id_usuario, accion) VALUES ($id_usuario, '$accion')");
    
    // Redirigir para evitar recargas accidentales
    header("Location: iniciosuperusuario.php");
    exit();
}

// Cambiar tipo de usuario
if (isset($_POST['cambiar_tipo_usuario'])) {
    $id = $_POST['id_usuario'];
    $nuevo_tipo = $_POST['tipo_usuario'];
    $conn->query("UPDATE usuarios SET tipo_usuario = '$nuevo_tipo' WHERE id_usuario = $id");
    
    $conn->query("INSERT INTO historial (id_usuario, accion, detalles) VALUES ($id, 'Cambio de tipo de usuario', 'Nuevo tipo: $nuevo_tipo')");
    header("Location: iniciosuperusuario.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Gestión de Usuarios</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">Agregar Usuario</button>
        <table class="table mt-3">
            <tr>
                <th>ID</th><th>Nombre</th><th>Correo</th><th>Cédula</th><th>Rol</th><th>Tipo Usuario</th><th>Acciones</th>
            </tr>
            <?php while ($row = $usuarios->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id_usuario'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['correo'] ?></td>
                    <td><?= $row['Cedula'] ?></td>
                    <td><?= $row['Rol'] ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">
                            <select name="tipo_usuario" onchange="this.form.submit()">
                                <option <?= $row['tipo_usuario'] == 'super_usuario' ? 'selected' : '' ?>>super_usuario</option>
                                <option <?= $row['tipo_usuario'] == 'admin' ? 'selected' : '' ?>>admin</option>
                                <option <?= $row['tipo_usuario'] == 'activos' ? 'selected' : '' ?>>activos</option>
                                <option <?= $row['tipo_usuario'] == 'visualizador' ? 'selected' : '' ?>>visualizador</option>
                            </select>
                            <input type="hidden" name="cambiar_tipo_usuario" value="1">
                        </form>
                    </td>
                    <td>
                        <a href="?reset_password=<?= $row['id_usuario'] ?>" class="btn btn-warning">Restablecer Contraseña</a>
                        <?php if ($row['estado'] == 'activo') { ?>
                            <a href="?cambiar_estado=<?= $row['id_usuario'] ?>&nuevo_estado=inactivo" class="btn btn-danger">Inactivar</a>
                        <?php } else { ?>
                            <a href="?cambiar_estado=<?= $row['id_usuario'] ?>&nuevo_estado=activo" class="btn btn-success">Activar</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Modal Agregar Usuario -->
    <div class="modal fade" id="modalAgregar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required>
                        <input type="email" name="correo" class="form-control mb-2" placeholder="Correo" required>
                        <input type="text" name="cedula" class="form-control mb-2" placeholder="Cédula" required>
                        <input type="text" name="rol" class="form-control mb-2" placeholder="Rol" required>
                        <select name="tipo_usuario" class="form-control mb-2" required>
                            <option value="super_usuario">super_usuario</option>
                            <option value="admin">admin</option>
                            <option value="activos">activos</option>
                            <option value="visualizador">visualizador</option>
                        </select>
                        <button type="submit" name="agregar_usuario" class="btn btn-primary">Agregar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <!-- Sección de Desplazamiento por app -->
    <div class="container mt-4">
        <h2>Desplazamiento por app</h2>
        <a href="../ingresousuariovisualizador/iniciovisualizador.php" class="btn btn-primary">Visualizar</a>
        <a href="../ingresousuarioadmin/inicioadmin.php" class="btn btn-primary">Sistemas</a>
        <a href="../ingresousuarioactivos/inicioactivos.php" class="btn btn-primary">Activos</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>