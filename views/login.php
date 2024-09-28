<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <h2>Bienvenido a Cuestionarios.got</h2>
            <p>Si no tienes cuenta por favor registrate e inicia sesión</p>
            <button class="login-btn" onclick="window.location.href='index.php?action=guardar'">Iniciar Sesión</button>
        </div>
        <div class="form-container">
            <h2>Login</h2>
            <form action="index.php?action=login" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>
                <label for="contrasenia">Contraseña:</label>
                <input type="password" name="contrasenia" required>
                <input type="submit" value="Ingresar">
            </form>
        </div>
    </div>
</body>
</html>


