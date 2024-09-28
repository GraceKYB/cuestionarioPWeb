<?php
require_once 'config/conexion.php';
require_once 'models/UsuarioModel.php';
require_once 'models/Usuario.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        global $conn;
        $this->usuarioModel = new UsuarioModel($conn);
    }

    public function registrarUsuario() {
        $nombre = $_POST['nombre'];
        $contrasenia = $_POST['contrasenia'];
        $perfil = $_POST['perfil'];

        $usuario = new Usuario(null, $nombre, $contrasenia, $perfil);
        $resultado = $this->usuarioModel->registrarUsuario($usuario);

        if ($resultado) {
            echo "<script>alert('Usuario registrado exitosamente.'); window.location.href = 'index.php';</script>";
        } else {
            echo "Error al registrar el usuario.";
        }
    }

    public function obtenerPerfiles() {
        return $this->usuarioModel->obtenerPerfiles();
    }

    public function login() {
        $nombre = $_POST['nombre'];
        $contrasenia = $_POST['contrasenia'];
    
        $usuario = $this->usuarioModel->verificarLogin($nombre, $contrasenia);
    
        if ($usuario) {
            session_start();
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['id_perfil'] = $usuario['id_perfil']; // Asegúrate de establecer id_perfil según tu lógica
    
            if ($usuario['id_perfil'] == 1) {
                $_SESSION['nombreEstudiante'] = $usuario['nombre'];
                header("Location: index.php?action=cuestionariosDisponibles");
            } elseif ($usuario['id_perfil'] == 2) {
                $_SESSION['nombreProfesor'] = $usuario['nombre'];
                header("Location: index.php?action=listar");
            } else {
                echo "Perfil no reconocido.";
            }
        } else {
            echo "Nombre o contraseña incorrectos.";
        }
    }
    
    
}
?>
