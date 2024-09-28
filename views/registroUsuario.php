<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="estilos/styleRegistro.css">
    <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
                e.preventDefault();
            }
        });
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && (e.key === 'J' || e.key === 'C' || e.key === 'K')) {
                e.preventDefault();
            }
            if (e.ctrlKey && e.key === 'U') {
                e.preventDefault();
            }
        });
        document.onkeydown = function(e) {
            if (e.ctrlKey && e.key === 'u') {
                return false;
            }
        };
    </script>
</head>
<body>
    <div class="container">
        <div class="welcome">
            <h2>Bienvenido</h2>
            <p>Para unirte a nuestra comunidad por favor Inicia Sesión con tus datos</p>
            <button class="login-btn" onclick="window.location.href='index.php'">Iniciar Sesión</button>
        </div>
        <div class="form-container">
            <h2>Registro de Usuario</h2>
            <form action="index.php?action=registrarUsuario" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>
                <label for="contrasenia">Contraseña:</label>
                <input type="password" name="contrasenia" required>
                <label for="perfil">Perfil:</label>
                <select name="perfil" required>
                    <?php
                    require_once 'controllers/UsuarioController.php';
                    $usuarioController = new UsuarioController();
                    $perfiles = $usuarioController->obtenerPerfiles();
                    foreach ($perfiles as $perfil) {
                        echo '<option value="' . $perfil['id_perfil'] . '">' . $perfil['nombre'] . '</option>';
                    }
                    ?>
                </select>
                <input type="submit" value="Registrar">
            </form>
        </div>
    </div>
</body>
</html>
