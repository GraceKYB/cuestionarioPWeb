<?php
class UsuarioModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrarUsuario($usuario) {
        $sql = "INSERT INTO usuarios (nombre, contrasenia, id_perfil) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conexion->error);
        }

        $nombre = $usuario->getNombre();
        $contrasenia = $usuario->getContrasenia();
        $perfil = $usuario->getPerfil();

        $stmt->bind_param("ssi", $nombre, $contrasenia, $perfil);
        $resultado = $stmt->execute();
        $stmt->close();
        
        return $resultado;
    }

    public function obtenerPerfiles() {
        $sql = "SELECT id_perfil, nombre FROM perfil";
        $result = $this->conexion->query($sql);

        $perfiles = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $perfiles[] = $row;
            }
        }
        return $perfiles;
    }

    public function verificarLogin($nombre, $contrasenia) {
        $sql = "SELECT * FROM usuarios WHERE nombre = ? AND contrasenia = ?";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param("ss", $nombre, $contrasenia);
        $stmt->execute();
        $result = $stmt->get_result();

        $usuario = null;
        if ($result && $result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
        }

        $stmt->close();
        return $usuario;
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

}
?>
