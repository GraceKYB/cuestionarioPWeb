<?php
if ($data->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Pregunta</th>
                <th>Opción</th>
                <th>Correcto</th>
            </tr>";
    while($row = $data->fetch_assoc()) {
        echo "<tr>
                <td>{$row['pregunta']}</td>
                <td>{$row['opcion']}</td>
                <td>" . ($row['correcto'] ? 'Sí' : 'No') . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No hay resultados.";
}
?>
