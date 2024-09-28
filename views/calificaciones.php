<!DOCTYPE html>
<html>
<head>
    <title>Resultados del Cuestionario</title>
    <link rel="stylesheet" href="estilos/styleCalifi.css">
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
<?php
    session_start();
    require_once '../models/CuestionarioModel.php';
    require_once '../config/conexion.php';

    $cuestionarioModel = new CuestionarioModel($conn);
    $idCuestionario = isset($_GET['id_cuestionario']) ? $_GET['id_cuestionario'] : null;
    $idUsuario = $_SESSION['id_usuario'];

    if ($idCuestionario) {
        // Obtiene el puntaje del usuario
        $resultado = $cuestionarioModel->getResultado($idCuestionario, $idUsuario);
    } else {
        $resultado = null;
    }
    ?>
    <h1>Calificaciones</h1>
    <link rel="stylesheet" href="estilos/styleCalifi.css">
    <?php if ($resultado): ?>
        <p>Su puntaje es: <?= htmlspecialchars($resultado['puntaje']) ?> puntos</p>
    <?php else: ?>
        <p>No se pudo obtener el puntaje.</p>
    <?php endif; ?>
</body>
</html>
