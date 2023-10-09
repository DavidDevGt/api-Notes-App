<?php
require_once('../models/Nota.php');

class NotaController
{
    private $notaModel;

    public function __construct()
    {
        $this->notaModel = new Nota();
    }

    /**
     * La función crea una nueva nota con un título, contenido e ID de usuario determinados.
     * 
     * @param titulo El parámetro "titulo" representa el título de la nota.
     * @param contenido El parámetro "contenido" representa el contenido o cuerpo de la nota. Es el
     * texto o información que se almacenará en la nota.
     * @param idUsuario El parámetro "idUsuario" representa el ID del usuario que está creando la nota.
     * 
     * @return una cadena codificada en JSON que contiene el estado y el mensaje de la operación.
     */
    public function crear($titulo, $contenido, $idUsuario)
    {
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
        }

        // Verificamos que el título y el contenido cumplan con la longitud mínima y máxima definida.
        if (strlen($datosNota['titulo']) < 3 || strlen($datosNota['titulo']) > 100 || strlen($datosNota['contenido']) < 10) {
            return false;
        }

        // Si todas las validaciones pasan, retornamos verdadero.
        return true;
    }
}