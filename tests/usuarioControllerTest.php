<?php
namespace MiProyecto\Tests;

use MiProyecto\Controllers\UsuarioController;
use MiProyecto\Models\Usuario;
use PHPUnit\Framework\TestCase;

class UsuarioControllerTest extends TestCase
{
    public function testCrearUsuarioExitosamente()
    {
        // Crear un mock del modelo Usuario.
        $usuarioMock = $this->createMock(Usuario::class);

        // Configurar el mock para devolver false para obtenerUsuario (indicando que el usuario no existe).
        $usuarioMock->method('obtenerUsuario')
            ->willReturn(false);

        // Configurar el mock para simular la creación de un usuario.
        $usuarioMock->method('crearNuevoUsuario')
            ->willReturn(true);

        // Inyectar el mock en el controlador.
        $usuarioController = new UsuarioController($usuarioMock);
        
        // Llamar al método crear y verificar el resultado esperado.
        $resultado = $usuarioController->crear('testUsername', 'testPassword');
        
        // Asegurar que la creación del usuario es exitosa.
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => 'success', 'message' => 'Usuario creado con éxito.']),
            $resultado
        );
    }

    public function testCrearUsuarioYaExistente()
    {
        // Crear un mock del modelo Usuario.
        $usuarioMock = $this->createMock(Usuario::class);

        // Configurar el mock para devolver true para obtenerUsuario (indicando que el usuario ya existe).
        $usuarioMock->method('obtenerUsuario')
            ->willReturn(true);

        // Inyectar el mock en el controlador.
        $usuarioController = new UsuarioController($usuarioMock);
        
        // Llamar al método crear y verificar el resultado esperado.
        $resultado = $usuarioController->crear('testUsername', 'testPassword');
        
        // Asegurar que se devuelve un error indicando que el usuario ya existe.
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => 'error', 'message' => 'El nombre de usuario ya está en uso.']),
            $resultado
        );
    }

    public function testCrearUsuarioConDatosInvalidos()
    {
        // Crear un mock del modelo Usuario.
        $usuarioMock = $this->createMock(Usuario::class);

        // No es necesario mockear obtenerUsuario o crearNuevoUsuario
        // ya que validarDatos debería fallar primero

        // Inyectar el mock en el controlador.
        $usuarioController = new UsuarioController($usuarioMock);
        
        // Llamar al método crear y verificar el resultado esperado.
        $resultado = $usuarioController->crear('', ''); // Datos inválidos
        
        // Asegurar que se devuelve un error indicando datos inválidos.
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => 'error', 'message' => 'Datos de usuario inválidos.']),
            $resultado
        );
    }
}
