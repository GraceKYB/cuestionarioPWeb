<?php
if ($data->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Usuario</th>
                <th>Cuestionario</th>
                <th>Pregunta</th>
                <th>Opción</th>
                <th>Puntaje</th>
            </tr>";
    while($row = $data->fetch_assoc()) {
        echo "<tr>
                <td>{$row['usuario_nombre']}</td>
                <td>{$row['cuestionario_titulo']}</td>
                <td>{$row['pregunta']}</td>
                <td>{$row['opcion']}</td>
                <td>{$row['puntaje']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No hay resultados.";
}
?>
