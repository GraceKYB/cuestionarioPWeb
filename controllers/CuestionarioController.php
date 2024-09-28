<?php

require_once 'config/conexion.php';
require_once 'models/CuestionarioModel.php';
require_once 'models/Usuario.php';

class CuestionarioController {
    private $cuestionarioModel;

    public function __construct() {
        global $conn;
        $this->cuestionarioModel = new CuestionarioModel($conn);
    }

    public function listar() {
        $cuestionarios = $this->cuestionarioModel->getAll();
        require 'views/cuestionarios/listar.php';
    }
   
    public function create() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $titulo = $_POST['titulo'];
            $id = $_POST['id_usuario'];
            $preguntas = $_POST['preguntas'];
            $opciones = $_POST['opciones'];
            $correctas = $_POST['correctas'];
    
            // Verificar si el id_usuario se está enviando correctamente
            if (empty($id)) {
                die("Error: id_usuario no se ha enviado.");
            }
    
            // Verificar si el usuario existe y obtener su información
            $usuario = $this->cuestionarioModel->getUsuarioById($id);
            if ($usuario) {
                // Verificar si el título es único
                if (!$this->cuestionarioModel->checkUniqueTitulo($titulo)) {
                    die("Error: El título '$titulo' ya existe. Por favor, elija otro título.");
                }
    
                // Guardar el cuestionario y obtener su ID
                $cuestionarioId = $this->cuestionarioModel->save($id, $titulo);
                
                // Verificar que $cuestionarioId no sea null
                if (!$cuestionarioId) {
                    die("Error: No se pudo crear el cuestionario.");
                }
    
                // Agregar preguntas y opciones
                foreach ($preguntas as $index => $pregunta) {
                    // Asegurarse de que $correctas[$index] sea un array
                    $correctasArray = isset ($correctas[$index]) && is_array($correctas[$index])? $correctas[$index] : [];
                    $this->cuestionarioModel->addQuestion($cuestionarioId, $pregunta, $opciones[$index], $correctasArray);
                }
    
                // Mensaje de éxito y redirección
                $_SESSION['mensaje'] = "Cuestionario creado exitosamente.";
                header("Location: index.php?action=listar"); // Redirigir a donde corresponda
                exit();
            } else {
                die("Error: El usuario con id_usuario = $id no existe o no tiene permisos para crear cuestionarios.");
            }
        } else {
            // Cargar la vista de creación de cuestionario, pasando el id_usuario si es necesario
            require 'views/cuestionarios/crear.php';
        }
    }
    public function listarCuestionariosDisponibles() {
        $cuestionarios = $this->cuestionarioModel->getCuestionariosDisponiblesParaEstudiante();
    
        require 'views/listadoCuestionarios.php';
    }
    

    public function responder() {
        $idCuestionario = $_GET['id'];
        $idUsuario = $_SESSION['id_usuario'];
    
        if ($this->cuestionarioModel->haRespondidoCuestionario($idUsuario, $idCuestionario)) {
            $_SESSION['mensaje'] = "Cuestionario respondido.";
            header("Location: index.php?action=listarCuestionariosDisponibles");
            exit();
        }
    
        $preguntas = $this->cuestionarioModel->getPreguntasByCuestionarioId($idCuestionario);
        require 'views/responder.php';
    }
    public function guardarRespuestas() {
        $idCuestionario = $_POST['id_cuestionario'];
        $idUsuario = $_SESSION['id_usuario'];
        $respuestas = $_POST['respuestas'];
        $puntaje = 0;
    
        // Obtén las preguntas del cuestionario
        $preguntas = $this->cuestionarioModel->getPreguntasByCuestionarioId($idCuestionario);
    
        // Calcula el puntaje basado en respuestas correctas
        $totalPreguntas = count($preguntas);
        $preguntasCorrectas = 0;
        $puntosPorPregunta = 2;  // Ajusta esto según el valor deseado por pregunta
    
        foreach ($preguntas as $pregunta) {
            $idPregunta = $pregunta['id_pregunta'];
            $idOpcion = $respuestas[$idPregunta];
            
            // Verifica si la respuesta es correcta
            $correcta = $this->cuestionarioModel->verificarRespuestaCorrecta($idPregunta, $idOpcion);
            if ($correcta) {
                $preguntasCorrectas++;
            }
            
            // Guarda la respuesta
            $this->cuestionarioModel->guardarRespuesta($idUsuario, $idCuestionario, $idPregunta, $idOpcion, $correcta);
        }
    
        // Calcula el puntaje
        $puntaje = $preguntasCorrectas * $puntosPorPregunta;
    
        // Guarda el puntaje en la base de datos
        $this->cuestionarioModel->guardarCalificacion($idUsuario, $idCuestionario, $puntaje);
    
        // Redirige a la página de resultados con el idCuestionario
        header("Location: views/calificaciones.php?id_cuestionario=$idCuestionario");
        exit();
    }
    
    
public function listarCalificaciones() {
    $calificaciones = $this->cuestionarioModel->obtenerCalificaciones();
    require 'views/calificaciones.php';
}

public function edit() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Procesar el formulario de edición
        $id_cuestionario = $_POST['id_cuestionario'];
        $titulo = $_POST['titulo'];
        $preguntas = $_POST['preguntas'];
        $opciones = $_POST['opciones'];
        $correctas = $_POST['correctas'];

        // Actualizar el cuestionario
        $this->cuestionarioModel->updateCuestionario($id_cuestionario, $titulo);

        // Actualizar preguntas y opciones
        foreach ($preguntas as $id_pregunta => $pregunta) {
            $this->cuestionarioModel->updatePregunta($id_pregunta, $pregunta);
            foreach ($opciones[$id_pregunta] as $id_opcion => $opcion) {
                $es_correcta = in_array($id_opcion, $correctas[$id_pregunta]) ? 1 : 0;
                $this->cuestionarioModel->updateOpcion($id_opcion, $opcion, $es_correcta);
            }
        }

        $_SESSION['mensaje'] = "Cuestionario actualizado exitosamente.";
        header("Location: index.php?action=listar");
        exit();
    } else {
        // Cargar el formulario de edición
        $id_cuestionario = $_GET['id'];
        $cuestionario = $this->cuestionarioModel->getCuestionarioById($id_cuestionario);
        $preguntas = $this->cuestionarioModel->getPreguntasByCuestionarioId($id_cuestionario);
        require 'views/cuestionarios/editar.php';
    }
}


public function verCuestionariosActualizados() {
    $idCuestionario = $_GET['id'];

    // Obtener el cuestionario y sus preguntas
    $datos = $this->cuestionarioModel->getQuestionsById($idCuestionario);

    $cuestionario = $datos['cuestionario'];
    $preguntas = $datos['preguntas'];

    // Cargar la vista
    require 'views/cuestionarios/actualizados.php';
}


public function activarCuestionario() {
    // Aquí solo mostramos un mensaje de activación
    $_SESSION['mensaje'] = "Cuestionario activado.";
    header("Location: index.php?action=listar");
    exit();
}

public function verCalificaciones() {
    $model = new CuestionarioModel($this->$conexion);
    $data = $model->getCalificaciones();
    include 'views/verCalificaciones.php';
}

public function verCuestionario($id_usuario, $id_cuestionario) {
    $model = new CuestionarioModel($this->$conexion);
    $data = $model->getCuestionarioResuelto($id_usuario, $id_cuestionario);
    include 'views/verCuestionario.php';
}
}
?>
