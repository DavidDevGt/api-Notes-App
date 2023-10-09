<?php
namespace MiProyecto\Tests;

use MiProyecto\Controllers\UsuarioController;
use MiProyecto\Models\Usuario;
use PHPUnit\Framework\TestCase;

class UsuarioControllerTest extends TestCase
{
    public function testCrearUsuario()
    {
        // Crear un mock del modelo Usuario.
        $usuarioModelMock = $this->createMock(Usuario::class);
        
        // Definir lo que debería devolver el método cuando se llame.
        $usuarioModelMock->method('obtenerUsuario')->willReturn(null);
        $usuarioModelMock->method('crearNuevoUsuario')->willReturn(true);
        
        // Pasar el mock al controlador en lugar de la implementación real.
        $usuarioController = new UsuarioController($usuarioModelMock);
        
        // Llamar al método y hacer la afirmación.
        $resultado = $usuarioController->crear('testUsername', 'testPassword');
        
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => 'success', 'message' => 'Usuario creado con éxito.']),
            $resultado
        );
    }
}
