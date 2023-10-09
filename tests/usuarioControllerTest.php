<?php
namespace MiProyecto\Tests;

use MiProyecto\UsuarioController;
use PHPUnit\Framework\TestCase;

class UsuarioControllerTest extends TestCase
{
    public function testCrearUsuario()
    {
        $usuarioController = new UsuarioController();
        $resultado = $usuarioController->crear('testUsername', 'testPassword');
        
        // Aseguramos que la creación del usuario es exitosa.
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => 'success', 'message' => 'Usuario creado con éxito.']),
            $resultado
        );
    }
}
