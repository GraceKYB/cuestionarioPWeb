<?php
class CuestionarioModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

   
    public function getById($id) {
        $sql = "SELECT * FROM cuestionarios WHERE id_cuestionario = ?";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();

        $stmt->close();
        return $cuestionario;
    }

    public function save($id_usuario, $titulo) {
        $stmt = $this->conexion->prepare("INSERT INTO cuestionarios (id_usuario, titulo) VALUES (?, ?)");
        $stmt->bind_param("is", $id_usuario, $titulo);
    
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            throw new mysqli_sql_exception($stmt->error);
        }
    }
    
    public function deleteById($id) {
        $sql = "DELETE FROM cuestionarios WHERE id_cuestionario = ?";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }

    public function addQuestion($cuestionarioId, $pregunta, $opciones, $correctas) {
        // Asegurarse de que $correctas sea un array
        if (!is_array($correctas)) {
            $correctas = explode(',', $correctas); // Convertir cadena a array si es necesario
        }
    
        // Insertar la pregunta en la tabla preguntas
        $stmt = $this->conexion->prepare("INSERT INTO preguntas (id_cuestionario, pregunta) VALUES (?, ?)");
        $stmt->bind_param("is", $cuestionarioId, $pregunta);
        $stmt->execute();
        $preguntaId = $stmt->insert_id;
        
        // Insertar opciones para la pregunta
        foreach ($opciones as $index => $opcion) {
            $esCorrecta = in_array($index, $correctas) ? 1 : 0; // Verificar si la opción es correcta
            $stmt = $this->conexion->prepare("INSERT INTO opciones (id_pregunta, opcion, correcta) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $preguntaId, $opcion, $esCorrecta);
            $stmt->execute();
        }
    }
    
    
    public function getQuestionsById($idCuestionario) {
        $sql = "
            SELECT q.id_cuestionario, q.titulo, p.id_pregunta, p.pregunta, o.id_opcion, o.opcion, o.correcta
            FROM cuestionarios q
            LEFT JOIN preguntas p ON q.id_cuestionario = p.id_cuestionario
            LEFT JOIN opciones o ON p.id_pregunta = o.id_pregunta
            WHERE q.id_cuestionario = ?
            ORDER BY p.id_pregunta, o.id_opcion
        ";
    
        $stmt = $this->conexion->prepare($sql);
    
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conexion->error);
        }
    
        $stmt->bind_param("i", $idCuestionario);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $cuestionario = null;
        $preguntas = [];
    
        while ($row = $result->fetch_assoc()) {
            if ($cuestionario === null) {
                $cuestionario = [
                    'id_cuestionario' => $row['id_cuestionario'],
                    'titulo' => $row['titulo']
                ];
            }
    
            $preguntaId = $row['id_pregunta'];
            if (!isset($preguntas[$preguntaId])) {
                $preguntas[$preguntaId] = [
                    'id_pregunta' => $preguntaId,
                    'pregunta' => $row['pregunta'],
                    'opciones' => []
                ];
            }
    
            if ($row['id_opcion']) {
                $preguntas[$preguntaId]['opciones'][] = [
                    'id_opcion' => $row['id_opcion'],
                    'opcion' => $row['opcion'],
                    'correcta' => $row['correcta']
                ];
            }
        }
    
        $stmt->close();
    
        return [
            'cuestionario' => $cuestionario,
            'preguntas' => array_values($preguntas) // Convertir el array asociativo a un array indexado
        ];
    }
    
    
    public function getUsuarioById($id) {
        $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $usuario = null;
        if ($result && $result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
        }

        $stmt->close();
        return $usuario;
    }
    
    public function checkUniqueTitulo($titulo) {
        $sql = "SELECT COUNT(*) as count FROM cuestionarios WHERE titulo = ?";
        $stmt = $this->conexion->prepare($sql);
    
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conexion->error);
        }
    
        $stmt->bind_param("s", $titulo);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
    
        $stmt->close();
    
        return $count == 0; // Retorna true si el título no existe (es único)
    }

    public function getAll() {
        $sql = "SELECT * FROM cuestionarios";
        $result = $this->conexion->query($sql);

        if ($result === false) {
            die("Error en la consulta: " . $this->conexion->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getCuestionariosDisponiblesParaEstudiante() {
        $sql = "SELECT c.id_cuestionario, c.titulo 
                FROM cuestionarios c
                JOIN usuarios u ON c.id_usuario = u.id_usuario
                WHERE u.id_perfil = 2"; // Aquí estamos asegurándonos de que el usuario que creó el cuestionario es un profesor
    
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $cuestionarios = [];
        while ($row = $result->fetch_assoc()) {
            $cuestionarios[] = $row;
        }
    
        $stmt->close();
        return $cuestionarios;
    }
    
    
    public function getPreguntasByCuestionarioId($idCuestionario) {
        $sql = "SELECT * FROM preguntas WHERE id_cuestionario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $idCuestionario);
        $stmt->execute();
        $result = $stmt->get_result();

        $preguntas = [];
        while ($pregunta = $result->fetch_assoc()) {
            $pregunta['opciones'] = $this->getOpcionesByPreguntaId($pregunta['id_pregunta']);
            $preguntas[] = $pregunta;
        }

        $stmt->close();
        return $preguntas;
    }

        

    public function getOpcionesByPreguntaId($idPregunta) {
        $sql = "SELECT * FROM opciones WHERE id_pregunta = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $idPregunta);
        $stmt->execute();
        $result = $stmt->get_result();

        $opciones =[];
        while($row =$result->fetch_assoc()) {
            $opciones[]=$row;
        }
        
        $stmt->close();

        return $opciones;
    }

    public function guardarRespuesta($idUsuario, $idCuestionario, $idPregunta, $idOpcion, $correcto) {
        $sql = "INSERT INTO resultados (id_usuario, id_cuestionario, id_pregunta, id_opcion, correcto) VALUES (?, ?, ?, ?,?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('iiiii', $idUsuario, $idCuestionario, $idPregunta, $idOpcion, $correcto);
        $stmt->execute();
        $stmt->close();
    }
    public function esOpcionCorrecta($idOpcion) {
        $sql = "SELECT correcta FROM opciones WHERE id_opcion = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $idOpcion);
        $stmt->execute();
        $result = $stmt->get_result();
        $correcto = $result->fetch_assoc()['correcta'];
        $stmt->close();
        return $correcto;
    }
    public function calcularPuntaje($idCuestionario, $idUsuario) {
        $sql = "SELECT SUM(correcto) as puntaje FROM resultados WHERE id_cuestionario = ? AND id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ii', $idCuestionario, $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $puntaje = $result->fetch_assoc()['puntaje'];
        $stmt->close();
        return $puntaje;
    }
    
public function verificarRespuestaCorrecta($idPregunta, $idOpcion) {
    $sql = "SELECT correcta FROM opciones WHERE id_pregunta = ? AND id_opcion = ?";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param('ii', $idPregunta, $idOpcion);
    $stmt->execute();
    $stmt->bind_result($correcta);
    $stmt->fetch();
    $stmt->close();
    return $correcta=1;
}

public function guardarCalificacion($idUsuario, $idCuestionario, $puntaje) {
    $sql = "INSERT INTO calificaciones (id_usuario, id_cuestionario, puntaje) VALUES (?, ?, ?)";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param('iii', $idUsuario, $idCuestionario, $puntaje);
    $stmt->execute();
    $stmt->close();
}

    public function obtenerCalificaciones() {
        $sql = "SELECT c.id_usuario, u.nombre as nombre_usuario, c.titulo as titulo_cuestionario, cal.puntaje
                FROM calificaciones cal
                JOIN usuarios u ON cal.id_usuario = u.id_usuario
                JOIN cuestionarios c ON cal.id_cuestionario = c.id_cuestionario";
        $result = $this->conexion->query($sql);

        $calificaciones = [];
        while ($row = $result->fetch_assoc()) {
            $calificaciones[] = $row;
        }
        return $calificaciones;
}
    public function haRespondidoCuestionario($idUsuario, $idCuestionario) {
        $sql = "SELECT COUNT(*) as count FROM resultados WHERE id_usuario = ? AND id_cuestionario = ?";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param('ii', $idUsuario, $idCuestionario);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];

        $stmt->close();

        return $count > 0; // Retorna true si ya ha respondido
}
    public function updateCuestionario($idCuestionario, $titulo) {
        $stmt = $this->conexion->prepare("UPDATE cuestionarios SET titulo = ? WHERE id_cuestionario = ?");
        $stmt->bind_param("si", $titulo, $idCuestionario);
        $stmt->execute();
}

public function updatePregunta($id_pregunta, $pregunta) {
    $sql = "UPDATE preguntas SET pregunta = ? WHERE id_pregunta = ?";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("si", $pregunta, $id_pregunta);
    return $stmt->execute();
}
public function updateOpcion($id_opcion, $opcion, $es_correcta) {
    $sql = "UPDATE opciones SET opcion = ?, correcta = ? WHERE id_opcion = ?";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("sii", $opcion, $es_correcta, $id_opcion);
    return $stmt->execute();
}

    public function getCuestionarioById($id_cuestionario) {
        $sql = "SELECT * FROM cuestionarios WHERE id_cuestionario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_cuestionario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


public function getRespuestasByCuestionarioAndUsuario($idUsuario,$idCuestionario) {
    $sql = "SELECT * FROM resultados WHERE id_usuario = ? AND id_cuestionario = ?";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param('ii', $idUsuario, $idCuestionario);
    $stmt->execute();
    $result = $stmt->get_result();
    $calificacion=$result->fetch_assoc();

    $stmt->close();
    return $calificacion;
}

public function getCalificacion($idUsuario, $idCuestionario) {
    $sql = "SELECT puntaje FROM calificaciones WHERE id_usuario = ? AND id_cuestionario = ?";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param('ii', $idUsuario, $idCuestionario);
    $stmt->execute();
    $result = $stmt->get_result();
    $calificacion = $result->fetch_assoc();
    $stmt->close();
    return $calificacion;
}
public function getPreguntas($cuestionario_id) {
    $sql = "SELECT * FROM preguntas WHERE id_cuestionario = ?";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param('i', $cuestionario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

public function getRespuestaCorrecta($pregunta_id) {
    $sql = "SELECT id_opcion FROM opciones WHERE id_pregunta = ? AND correcta = 1";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param('i', $pregunta_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $respuesta = $result->fetch_assoc();
    return $respuesta['id_opcion'];
}

public function guardarResultado($cuestionario_id, $usuario_id, $puntaje) {
    $sql = "INSERT INTO calificaciones (id_cuestionario, id_usuario, puntaje) VALUES (?, ?, ?)";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param('iii', $cuestionario_id, $usuario_id, $puntaje);
    return $stmt->execute();
}
public function getResultado($idCuestionario, $idUsuario) {
    $query = "SELECT puntaje FROM calificaciones WHERE id_cuestionario = ? AND id_usuario = ?";
    $stmt = $this->conexion->prepare($query);
    $stmt->bind_param("ii", $idCuestionario, $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
public function getCalificaciones() {
    $query = "
    SELECT 
        u.nombre AS usuario_nombre, 
        c.titulo AS cuestionario_titulo,
        p.pregunta AS pregunta,
        o.opcion AS opcion,
        cal.puntaje AS puntaje
    FROM calificaciones cal
    JOIN usuarios u ON cal.id_usuario = u.id_usuario
    JOIN cuestionarios c ON cal.id_cuestionario = c.id_cuestionario
    JOIN resultados r ON r.id_cuestionario = c.id_cuestionario AND r.id_usuario = u.id_usuario
    JOIN preguntas p ON r.id_pregunta = p.id_pregunta
    JOIN opciones o ON r.id_opcion = o.id_opcion
    ";
    return $this->conexion->query($query);
}

public function getCuestionarioResuelto($id_usuario, $id_cuestionario) {
    $query = "
    SELECT 
        p.pregunta AS pregunta,
        o.opcion AS opcion,
        r.correcto AS correcto
    FROM resultados r
    JOIN preguntas p ON r.id_pregunta = p.id_pregunta
    JOIN opciones o ON r.id_opcion = o.id_opcion
    WHERE r.id_usuario = ? AND r.id_cuestionario = ?
    ";
    $stmt = $this->conexion->prepare($query);
    $stmt->bind_param('ii', $id_usuario, $id_cuestionario);
    $stmt->execute();
    return $stmt->get_result();
}
}
?>