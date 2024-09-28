<?php

session_start();
if (isset($_SESSION['mensaje'])) {
    echo '<p>' . $_SESSION['mensaje'] . '</p>';
    unset($_SESSION['mensaje']);
}
require_once 'config/conexion.php';
require_once 'models/UsuarioModel.php';
require_once 'models/CuestionarioModel.php';
require_once 'controllers/UsuarioController.php';
require_once 'controllers/CuestionarioController.php';

$usuarioController = new UsuarioController();
$cuestionarioController = new CuestionarioController();

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'registrarUsuario':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $usuarioController->registrarUsuario();
            } else {
                echo "Método no permitido";
            }
            break;
        case 'login':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $usuarioController->login();
            } else {
                require_once 'views/login.php';
            }
            break;
        case 'guardar':
            require __DIR__ . '/views/registroUsuario.php';
            break;
        case 'listar':
            $cuestionarioController->listar();
            break;
        case 'create':
            $cuestionarioController->create();
            break;
        case 'cuestionariosDisponibles':
            if ($_SESSION['id_perfil'] == 1) { // Solo los estudiantes pueden ver los cuestionarios disponibles
                $cuestionarioController->listarCuestionariosDisponibles();
            } else {
                echo "Acción no permitida";
            }
            break;
            
        case 'cuestionarioDisponible':
            $cuestionarioController->listarCuestionariosDisponibles();
            breaK;
        case 'responder':
            $cuestionarioController->responder();
            break;
        case 'guardarRespuestas':
            $cuestionarioController->guardarRespuestas();
            break;
        
        case 'resultados':
            $cuestionarioController->mostrarResultados();
            break;
        case 'listarCalificaciones':
            $cuestionarioController->listarCalificaciones();
            break;
            case 'salir':
                session_start();
                session_unset();
                session_destroy();
                header("Location: index.php?action=login");
                exit();
                break;
        case 'edit':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $cuestionarioController->edit();
            } else {
                $cuestionarioController->edit();
            }
            break;
        case 'verCuestionariosActualizados':
            $cuestionarioController->verCuestionariosActualizados();
            break;
        case 'activarCuestionario':
            $cuestionarioController->activarCuestionario();
            break;
        case 'verCalificaciones':
            $cuestionarioController->verCalificaciones();
            break;
        case 'verCuestionario':
            if (isset($_GET['id_usuario']) && isset($_GET['id_cuestionario'])) {
                $cuestionarioController->verCuestionario($_GET['id_usuario'], $_GET['id_cuestionario']);
            } else {
                echo "Faltan parámetros.";
            }
            break;
        default:
            echo "Acción no permitida";
            break;
}
} else {
// Si no se especifica 'action', mostrar la vista de login por defecto
require 'views/login.php';
}
?>
