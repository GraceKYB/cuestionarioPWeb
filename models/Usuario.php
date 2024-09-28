<?php
class Usuario {
    private $id;
    private $nombre;
    private $contrasenia;
    private $perfil;

    public function __construct($id, $nombre, $contrasenia, $perfil) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->contrasenia = $contrasenia;
        $this->perfil = $perfil;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getContrasenia() {
        return $this->contrasenia;
    }

    public function getPerfil() {
        return $this->perfil;
    }
}
?>
