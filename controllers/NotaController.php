<?php
namespace MiProyecto\Controllers;

use MiProyecto\Models\Nota;

class NotaController
{
    private $notaModel;

    // Esta se usa para testing
    public function __construct($notaModel = null)
    {
        $this->notaModel = $notaModel ?? new Nota();
    }

    // Descomentar para producción

    // public function __construct()
    // {
    //     $this->notaModel = new Nota();
    // }

    /**
     * La función crea una nueva nota con un título, contenido e ID de usuario determinados y devuelve
     * un mensaje de éxito.
     * 
     * @param titulo El título de la nota.
     * @param contenido El parámetro "contenido" representa el contenido o cuerpo de la nota. Es el
     * texto o información principal que contiene la nota.
     * @param idUsuario El parámetro "idUsuario" representa el ID del usuario que está creando la nota.
     * Se utiliza para asociar la nota con el usuario en la base de datos.
     * 
     * @return una cadena codificada en JSON. La cadena contiene un estado y un mensaje que indica el
     * éxito o el fracaso de la creación de una nota.
     */
    public function crear($titulo, $contenido, $idUsuario)
    {
        // Validamos los datos de la nota antes de intentar crearla.
        if (!$this->validarDatosNota(['titulo' => $titulo, 'contenido' => $contenido])) {
            return json_encode(['status' => 'error', 'message' => 'Datos de nota inválidos.']);
        }

        // Creamos la nota y devolvemos un mensaje de éxito.
        $this->notaModel->crearNuevaNota($titulo, $contenido, $idUsuario);
        return json_encode(['status' => 'success', 'message' => 'Nota creada con éxito.']);
    }

    /**
     * La función "leer" recupera una nota por su ID y la devuelve como un objeto JSON, junto con un
     * estado de éxito, o devuelve un mensaje de error si no se encuentra la nota.
     * 
     * @param idNota El parámetro "idNota" es el identificador de la nota que queremos leer. Se utiliza
     * para recuperar la nota específica de la base de datos.
     * 
     * @return una cadena codificada en JSON. Si se encuentra la nota, devuelve un estado de éxito y
     * los datos de la nota. Si no se encuentra la nota, devuelve un estado de error y un mensaje
     * indicando que no se encontró la nota.
     */
    public function leer($idNota)
    {
        $nota = $this->notaModel->leerNota($idNota);
        if ($nota) {
            return json_encode(['status' => 'success', 'nota' => $nota]);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Nota no encontrada.']);
        }
    }

    /**
     * La función "actualizar" actualiza el título y el contenido de una nota en una aplicación PHP.
     * 
     * @param idNota La identificación de la nota que necesita ser actualizada.
     * @param nuevoTitulo El parámetro "nuevoTitulo" representa el nuevo título de la nota que necesita
     * ser actualizada.
     * @param nuevoContenido El parámetro `` representa el nuevo contenido de la nota
     * que necesita ser actualizado.
     * 
     * @return una cadena codificada en JSON que contiene el estado y el mensaje de la operación.
     */
    public function actualizar($idNota, $nuevoTitulo, $nuevoContenido)
    {
        $this->notaModel->actualizarNota($idNota, $nuevoTitulo, $nuevoContenido);
        return json_encode(['status' => 'success', 'message' => 'Nota actualizada con éxito.']);
    }

    /**
     * La función "eliminar" elimina una nota utilizando el ID proporcionado y devuelve una respuesta
     * codificada en JSON que indica el éxito.
     * 
     * @param idNota El parámetro "idNota" es el ID de la nota que debe eliminarse.
     * 
     * @return una cadena codificada en JSON que contiene el estado y el mensaje de la operación.
     */
    public function eliminar($idNota)
    {
        $this->notaModel->borrarNota($idNota);
        return json_encode(['status' => 'success', 'message' => 'Nota eliminada con éxito.']);
    }

    private function validarDatosNota($datosNota)
    {
        // Verificamos que los datos de la nota no estén vacíos.
        if (empty($datosNota['titulo']) || empty($datosNota['contenido'])) {
            return false;
        } // Caracteres minimos van AQUI
        if (strlen($datosNota['titulo']) < 7 || strlen($datosNota['titulo']) > 60 || strlen($datosNota['contenido']) < 10) {
            return false;
        }

        // Si todas las validaciones pasan, retornamos verdadero.
        return true;
    }
}