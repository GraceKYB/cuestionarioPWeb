<!DOCTYPE html>
<html>
<head>
    <title>Responder Cuestionario</title>
    <link rel="stylesheet" href="estilos/styleResponder.css">
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
        <h1>Lea y conteste el siguiente cuestionario</h1>
        <h5>Cada pregunta corresponde a 1 punto</h5>
        <form action="index.php?action=guardarRespuestas" method="post">
            <input type="hidden" name="id_cuestionario" value="<?php echo $_GET['id']; ?>">
            <?php foreach ($preguntas as $pregunta) { ?>
                <div class="question">
                    <fieldset>
                        <legend><?php echo $pregunta['pregunta']; ?></legend>
                        <?php foreach ($pregunta['opciones'] as $opcion) { ?>
                            <div class="form-group">
                                <label>
                                    <input type="radio" name="respuestas[<?php echo $pregunta['id_pregunta']; ?>]" value="<?php echo $opcion['id_opcion']; ?>">
                                    <?php echo $opcion['opcion']; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </fieldset>
                </div>
            <?php } ?>
            <button type="submit" class="submit-btn">Enviar Respuestas</button>
        </form>
    </div>
</body>
</html>
