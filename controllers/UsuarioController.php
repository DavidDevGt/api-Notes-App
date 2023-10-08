<?php
require_once('../models/Usuario.php');

class UsuarioController {
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }

    public function crear($nombreUsuario, $contrasena)
    {
        if(!$this->validarDatos($nombreUsuario, $contrasena)) {
            return json_encode(['status' => 'error', 'message' => 'Datos de usuario inválidos.']);
        }

        $this->usuarioModel->crearNuevoUsuario($nombreUsuario, $contrasena);
        return json_encode(['status' => 'success', 'message' => 'Usuario creado con éxito.']);
    }



    private function validarDatos($nombreUsuario, $contrasena) {
        return true; // Falta agregar la logica aca
    }
}