<?php
require_once('../config/db.php');

class Nota
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conexion->connect_error) {
            die("Error al conectar a la base de datos: " . $this->conexion->connect_error);
        }

        $this->conexion->set_charset("utf8");
    }

    public function __destruct()
    {
        // Cerrar la conexión al destruir la instancia de la clase.
        $this->conexion->close();
    }

    /**
     * La función `crearNuevaNota` inserta una nueva nota en la base de datos con el título, contenido
     * e ID de usuario dados.
     * 
     * @param titulo El parámetro "titulo" representa el título de la nota.
     * @param contenido El parámetro "contenido" representa el contenido o cuerpo de la nota que deseas
     * crear. Es el texto o información que deseas almacenar en la tabla de "notas".
     * @param idUsuario El parámetro "idUsuario" es el ID del usuario que está creando la nueva nota.
     */
    public function crearNuevaNota($titulo, $contenido, $idUsuario)
    {
        $stmt = $this->conexion->prepare("INSERT INTO notas (titulo, contenido, idUsuario) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $titulo, $contenido, $idUsuario);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * La función "leerNota" recupera una nota de la base de datos según su ID y devuelve el resultado
     * como una matriz asociativa.
     * 
     * @param idNota El parámetro "idNota" es el identificador único de la nota que desea recuperar de
     * la base de datos. Se utiliza en la consulta SQL para filtrar los resultados y recuperar la nota
     * con el valor idNota coincidente.
     * 
     * @return contiene los datos de la nota con la identificación
     * especificada. Si no hay resultados, devolverá NULL.
     */
    public function leerNota($idNota)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM notas WHERE idNota = ?");
        $stmt->bind_param("i", $idNota);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();

        // Si no hay resultados, devuelve NULL
        return $resultado->fetch_assoc();
    }

    /**
     * La función "actualizarNota" actualiza el título y contenido de una nota en una base de datos.
     * 
     * @param idNota La identificación de la nota que necesita ser actualizada.
     * @param nuevoTitulo El parámetro "nuevoTitulo" es el nuevo título que deseas actualizar para la
     * nota.
     * @param nuevoContenido El parámetro "nuevoContenido" es el nuevo contenido que desea actualizar
     * para la nota con el "idNota" dado.
     */
    public function actualizarNota($idNota, $nuevoTitulo, $nuevoContenido)
    {
        $stmt = $this->conexion->prepare("UPDATE notas SET titulo = ?, contenido = ? WHERE idNota = ?");
        $stmt->bind_param("ssi", $nuevoTitulo, $nuevoContenido, $idNota);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * La función "borrarNota" actualiza la columna "activo" de la tabla "notas" a 0 para una "idNota"
     * determinada.
     * 
     * @param idNota El parámetro "idNota" es el ID de la nota que desea eliminar.
     */
    public function borrarNota($idNota)
    {
        $stmt = $this->conexion->prepare("UPDATE notas SET activo = 0 WHERE id = ?");
        $stmt->bind_param("i", $idNota);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * La función "obtenerNotasPorUsuario" recupera todas las notas activas de un usuario específico
     * desde la base de datos y devuelve los resultados como una matriz de matrices asociativas.
     * 
     * @param idUsuario El parámetro "idUsuario" es el ID del usuario cuyas notas queremos recuperar
     * de la base de datos.
     * 
     * @return una matriz de matrices asociativas que contiene los datos de todas las notas del usuario
     * especificado que están marcadas como activas.
     */
    public function obtenerNotasPorUsuario($idUsuario) {
        $stmt = $this->conexion->prepare("SELECT * FROM notas WHERE idUsuario = ? AND activo = 1");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $stmt->close();

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * La función "obtenerNotaPorTitulo" busca en la base de datos una nota con un título específico
     * y devuelve los resultados como una matriz asociativa.
     * 
     * @param titulo El parámetro "titulo" es el título de la nota que queremos buscar en la base de datos.
     * 
     * @return una matriz asociativa que contiene los datos de la nota con el título especificado. Si no
     * hay resultados, devolverá NULL.
     */
    public function obtenerNotaPorTitulo($titulo) {
        $stmt = $this->conexion->prepare("SELECT * FROM notas WHERE titulo = ? AND activo = 1");
        $stmt->bind_param("s", $titulo);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $stmt->close();

        return $resultado->fetch_assoc();
    }
}
