<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cuestionario</title>
    <link rel="stylesheet" href="estilos/styleEditar.css">
</head>
<body>
    <div class="container">
        <h1>Editar Cuestionario</h1>
        <form method="post" action="index.php?action=edit">
            <input type="hidden" name="id_cuestionario" value="<?php echo htmlspecialchars($cuestionario['id_cuestionario'], ENT_QUOTES, 'UTF-8'); ?>">

            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($cuestionario['titulo'], ENT_QUOTES, 'UTF-8'); ?>" required>

            <div id="preguntas-container">
                <?php foreach ($preguntas as $pregunta): ?>
                    <div class="pregunta">
                        <label for="pregunta_<?php echo htmlspecialchars($pregunta['id_pregunta'], ENT_QUOTES, 'UTF-8'); ?>">Pregunta:</label>
                        <input type="text" name="preguntas[<?php echo htmlspecialchars($pregunta['id_pregunta'], ENT_QUOTES, 'UTF-8'); ?>]" id="pregunta_<?php echo htmlspecialchars($pregunta['id_pregunta'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars($pregunta['pregunta'], ENT_QUOTES, 'UTF-8'); ?>" required>

                        <!-- Opciones -->
                        <?php if (isset($pregunta['opciones']) && !empty($pregunta['opciones'])): ?>
                            <div class="opciones-container">
                                <?php foreach ($pregunta['opciones'] as $opcion): ?>
                                    <div class="opcion">
                                        <label for="opcion_<?php echo htmlspecialchars($pregunta['id_pregunta'], ENT_QUOTES, 'UTF-8'); ?>_<?php echo htmlspecialchars($opcion['id_opcion'], ENT_QUOTES, 'UTF-8'); ?>">Opción:</label>
                                        <input type="text" name="opciones[<?php echo htmlspecialchars($pregunta['id_pregunta'], ENT_QUOTES, 'UTF-8'); ?>][<?php echo htmlspecialchars($opcion['id_opcion'], ENT_QUOTES, 'UTF-8'); ?>]" id="opcion_<?php echo htmlspecialchars($pregunta['id_pregunta'], ENT_QUOTES, 'UTF-8'); ?>_<?php echo htmlspecialchars($opcion['id_opcion'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars($opcion['opcion'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="checkbox" name="correctas[<?php echo htmlspecialchars($pregunta['id_pregunta'], ENT_QUOTES, 'UTF-8'); ?>][]" value="<?php echo htmlspecialchars($opcion['id_opcion'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $opcion['correcta'] ? 'checked' : ''; ?>>
                                        Correcta
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>No hay opciones para esta pregunta.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
