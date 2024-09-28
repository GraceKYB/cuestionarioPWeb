<!DOCTYPE html>
<html>
<head>
    <title>Cuestionarios Disponibles</title>
    <link rel="stylesheet" href="estilos/styleCuesDispo.css">
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
        <h1>Cuestionarios Disponibles</h1>
        <a href="index.php?action=login" class="logout-button">Salir</a>
        <?php if (!empty($cuestionarios)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cuestionarios as $cuestionario) { ?>
                        <tr>
                            <td><?php echo $cuestionario['id_cuestionario']; ?></td>
                            <td><?php echo $cuestionario['titulo']; ?></td>
                            <td>
                                <a href="index.php?action=responder&id=<?php echo $cuestionario['id_cuestionario']; ?>">Responder</a>
                                <a href="index.php?action=verCalificaciones&id=<?php echo $cuestionario['id_cuestionario']; ?>">Ver Calificación</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="no-cuestionarios">No hay cuestionarios disponibles.</p>
        <?php } ?>
    </div>
</body>
</html>
