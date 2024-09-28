<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionario Actualizado</title>
    <link rel="stylesheet" href="estilos/styleActualizado.css">
</head>
<body>
    <div class="container">
        <h1>Cuestionario Actualizado</h1>
        <h2>Título: <?php echo htmlspecialchars($cuestionario['titulo']); ?></h2>

        <div id="preguntas-container">
            <?php foreach ($preguntas as $index => $pregunta) { ?>
                <div class="pregunta">
                    <h3>Pregunta: <?php echo htmlspecialchars($pregunta['pregunta']); ?></h3>

                    <div class="opciones-container">
                        <?php if (!empty($pregunta['opciones'])) { ?>
                            <?php foreach ($pregunta['opciones'] as $opcion) { ?>
                                <div class="opcion">
                                    <p>Opción: <?php echo htmlspecialchars($opcion['opcion']); ?></p>
                                    <p>
                                        Correcta: 
                                        <?php echo $opcion['correcta'] ? 'Sí' : 'No'; ?>
                                    </p>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <p>No hay opciones para esta pregunta.</p>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
