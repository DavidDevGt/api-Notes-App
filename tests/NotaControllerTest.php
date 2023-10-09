<?php
namespace MiProyecto\Tests;

use MiProyecto\Controllers\NotaController;
use PHPUnit\Framework\TestCase;

class NotaControllerTest extends TestCase
{
    public function testCrearNota()
    {
        // Crear un mock del modelo Nota.
        $notaMock = $this->createMock(\MiProyecto\Models\Nota::class);

        // Configurar el mock para simular la creación de una nota y esperar que el método sea llamado una vez.
        $notaMock->expects($this->once())
            ->method('crearNuevaNota')
            ->with(
                $this->equalTo('testTitulo'),
                $this->equalTo('testContenido'),
                $this->equalTo(1)
            )
            ->willReturn(true);

        // Inyectar el mock en el controlador.
        $notaController = new NotaController($notaMock);

        // Llamar al método crear y verificar el resultado esperado.
        $resultado = $notaController->crear('testTitulo', 'testContenido', 1);

        // Asegurar que la creación de la nota es exitosa.
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => 'success', 'message' => 'Nota creada con éxito.']),
            $resultado
        );
    }

    public function testCrearNotaConDatosInvalidos()
    {
        // Crear un mock del modelo Nota.
        $notaMock = $this->createMock(\MiProyecto\Models\Nota::class);

        /* No hay que configurar el comportamiento del mock aca, ya que
        el controlador debería devolver un error antes de intentar llamar
        a cualquier método del modelo debido a los datos de entrada inválidos */

        // Inyectar el mock en el controlador.
        $notaController = new NotaController($notaMock);

        // Llamar al método crear con datos inválidos y verificar el resultado esperado.
        $resultado = $notaController->crear('', '', 1);

        // Asegurar que la creación de la nota falla y devuelve el mensaje de error correcto.
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => 'error', 'message' => 'Datos de nota inválidos.']),
            $resultado
        );
    }


}