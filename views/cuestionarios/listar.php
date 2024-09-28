<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Cuestionarios</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    width: 80%;
    margin: auto;
    overflow: hidden;
    padding: 20px;
    background: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
}

a {
    color: #fff;
    text-decoration: none;
    display: inline-block;
    margin: 10px 5px;
    padding: 10px 15px;
    border-radius: 5px;
    background-color: #28a745; /* Color verde */
    transition: background-color 0.3s;
}

a:hover {
    background-color: #218838; /* Color verde más oscuro */
}

.message {
    text-align: center;
    color: #333;
    font-size: 1.2em;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background: #fff;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th {
    background-color: #f2f2f2;
}

td a {
    color: #fff;
    background-color: #007bff; /* Color azul */
    border-radius: 3px;
    margin: 0 5px; /* Espacio entre enlaces */
    padding: 5px 10px;
    transition: background-color 0.3s;
}

td a:hover {
    background-color: #0056b3; /* Color azul más oscuro */
}

    </style>
    <link rel="stylesheet" href="estilos/styleListado.css">
    
</head>
<body>
    <div class="container">
        <h1>Listado de Cuestionarios</h1>
        <a href="index.php?action=create" >Crear Nuevo Cuestionario</a>
        <a href="index.php?action=login">SALIR</a>
        
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
                                <a href="index.php?action=edit&id=<?php echo $cuestionario['id_cuestionario']; ?>">Editar</a>
                                <a href="index.php?action=verCuestionariosActualizados&id=<?php echo $cuestionario['id_cuestionario']; ?>">Ver</a>
                                <!-- <a href="index.php?action=activarCuestionario&id=<?php echo $cuestionario['id_cuestionario']; ?>">Activar</a> -->
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="message">No hay cuestionarios disponibles.</div>
        <?php } ?>
    </div>
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
</body>
</html>
