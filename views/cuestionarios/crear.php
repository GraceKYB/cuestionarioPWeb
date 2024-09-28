<!DOCTYPE html>
<html>
<head>
    <title>Crear Cuestionario</title>
    <link rel="stylesheet" href="estilos/styleCuestionario.css">
</head>
<body>
    <form action="index.php?action=create" method="post">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" required><br>

        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

        <div id="preguntas-container">
            <!-- Contenedor inicial de preguntas -->
            <div class="pregunta">
                <label for="preguntas[]">Pregunta:</label>
                <input type="text" name="preguntas[]" required><br>

                <label>Opciones:</label>
                <div class="opciones-container">
                    <div class="opcion">
                        <input type="checkbox" name="correctas[0][]" value="0">
                        <input type="text" name="opciones[0][]" required>
                        <button type="button" onclick="removeOpcion(this)">X</button>
                    </div>
                </div>
                <button type="button" onclick="addOpcion(this)">Añadir Opción</button><br>
            </div>
        </div>

        <button type="button" onclick="addPregunta()">Añadir Pregunta</button><br><br>

        <input type="submit" value="Guardar Cuestionario">
    </form>

    <script>
        var preguntaIndex = 1;

        function addPregunta() {
            var container = document.getElementById('preguntas-container');
            var preguntaDiv = document.createElement('div');
            preguntaDiv.className = 'pregunta';

            preguntaDiv.innerHTML = `
                <label for="preguntas[]">Pregunta:</label>
                <input type="text" name="preguntas[]" required><br>

                <label>Opciones:</label>
                <div class="opciones-container">
                    <div class="opcion">
                        <input type="checkbox" name="correctas[${preguntaIndex}][]" value="0">
                        <input type="text" name="opciones[${preguntaIndex}][]" required>
                        <button type="button" onclick="removeOpcion(this)">X</button>
                    </div>
                </div>
                <button type="button" onclick="addOpcion(this)">Añadir Opción</button><br>
            `;

            container.appendChild(preguntaDiv);
            preguntaIndex++;
        }

        function addOpcion(button) {
            var opcionesContainer = button.previousElementSibling;
            var preguntaIndex = opcionesContainer.getElementsByTagName('input')[0].name.match(/\d+/)[0];
            var opcionIndex = opcionesContainer.getElementsByTagName('input').length / 2; // Ajustar para el índice de la opción

            var opcionDiv = document.createElement('div');
            opcionDiv.className = 'opcion';

            var checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = `correctas[${preguntaIndex}][]`;
            checkbox.value = opcionIndex;

            var newOpcion = document.createElement('input');
            newOpcion.type = 'text';
            newOpcion.name = `opciones[${preguntaIndex}][]`;
            newOpcion.required = true;

            var removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = 'X';
            removeButton.onclick = function() {
                removeOpcion(this);
            };

            opcionDiv.appendChild(checkbox);
            opcionDiv.appendChild(newOpcion);
            opcionDiv.appendChild(removeButton);

            opcionesContainer.appendChild(opcionDiv);
        }

        function removeOpcion(button) {
            var opcionDiv = button.parentElement;
            opcionDiv.remove();
        }
    </script>
</body>
</html>
