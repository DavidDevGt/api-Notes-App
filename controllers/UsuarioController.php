<?php
namespace MiProyecto\Controllers;

use MiProyecto\Models\Usuario;

class UsuarioController
{
    private $usuarioModel;

    // Esta se usa para testing
    public function __construct($usuarioModel = null)
    {
        $this->usuarioModel = $usuarioModel ?? new Usuario();
    }

    // Descomentar para producción

    // public function __construct()
    // {
    //     $this->usuarioModel = new Usuario();
    // }


    /**
     * La función "crear" crea un nuevo usuario con un nombre de usuario y contraseña determinados,
     * valida los datos proporcionados por el usuario, verifica si el nombre de usuario ya está en uso
     * y devuelve una respuesta codificada en JSON indicando el estado y mensaje de la operación.
     * 
     * @param nombreUsuario El parámetro "nombreUsuario" representa el nombre de usuario del usuario
     * que se está creando.
     * @param contrasena El parámetro "contrasena" representa la contraseña del usuario.
     * 
     * @return una cadena codificada en JSON. La cadena contiene un estado y un mensaje que indica el
     * resultado de la operación.
     */
    public function crear($nombreUsuario, $contrasena)
    {
        // Validamos los datos proporcionados por el usuario.
        if (!$this->validarDatos($nombreUsuario, $contrasena)) {
            return json_encode(['status' => 'error', 'message' => 'Datos de usuario inválidos.']);
        }

        // Verificamos que el nombre de usuario no esté ya en uso.
        if ($this->usuarioModel->obtenerUsuario($nombreUsuario)) {
            return json_encode(['status' => 'error', 'message' => 'El nombre de usuario ya está en uso.']);
        }

        // Creamos el nuevo usuario y devolvemos un mensaje de éxito.
        $this->usuarioModel->crearNuevoUsuario($nombreUsuario, $contrasena);
        return json_encode(['status' => 'success', 'message' => 'Usuario creado con éxito.']);
    }

    /**
     * La función "obtener" en PHP recupera un usuario por su nombre de usuario y devuelve una
     * respuesta JSON que indica éxito o error.
     * 
     * @param nombreUsuario El parámetro "nombreUsuario" es una cadena que representa el nombre de
     * usuario del usuario que queremos obtener.
     * 
     * @return una cadena codificada en JSON. Si se encuentra al usuario, devuelve un estado de éxito
     * junto con el objeto de usuario. Si no se encuentra el usuario, devuelve un estado de error junto
     * con un mensaje que indica que no se encontró el usuario.
     */
    public function obtener($nombreUsuario)
    {
        // Validar el username
        $usuario = $this->usuarioModel->obtenerUsuario($nombreUsuario);
        if ($usuario) {
            return json_encode(['status' => 'success', 'usuario' => $usuario]);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
        }
    }

    /**
     * La función "actualizar" actualiza la contraseña de un nombre de usuario determinado y devuelve
     * un mensaje de éxito en formato JSON.
     * 
     * @param nombreUsuario El parámetro "nombreUsuario" representa el nombre de usuario del usuario
     * cuya contraseña necesita ser actualizada.
     * @param nuevaContrasena El parámetro "nuevaContrasena" representa la nueva contraseña que se
     * actualizará para el usuario.
     * 
     * @return una cadena codificada en JSON que contiene el estado y el mensaje de la operación.
     */
    public function actualizar($nombreUsuario, $nuevaContrasena)
    {
        // validar datos...
        $this->usuarioModel->actualizarUsuario($nombreUsuario, $nuevaContrasena);
        return json_encode(['status' => 'success', 'message' => 'Contraseña actualizada con éxito.']);
    }

    /**
     * La función "eliminar" elimina un usuario de la base de datos y devuelve un mensaje de éxito en
     * formato JSON.
     * 
     * @param nombreUsuario El parámetro "nombreUsuario" es una cadena que representa el nombre de
     * usuario del usuario a eliminar.
     * 
     * @return una cadena codificada en JSON que contiene el estado y el mensaje de la operación.
     */
    public function eliminar($nombreUsuario)
    {
        // validar nombreUsuario
        $this->usuarioModel->borrarUsuario($nombreUsuario);
        return json_encode(['status' => 'success', 'message' => 'Usuario eliminado con éxito.']);
    }

    /**
     * La función de inicio de sesión en PHP valida las credenciales del usuario, recupera datos del
     * usuario de la base de datos y genera un token JWT si las credenciales son válidas.
     * 
     * @param nombreUsuario El parámetro "nombreUsuario" representa el nombre de usuario del usuario
     * que intenta iniciar sesión.
     * @param contrasena El parámetro "contrasena" representa la contraseña del usuario que intenta
     * iniciar sesión.
     * 
     * @return una cadena codificada en JSON. El contenido de la cadena depende de las condiciones
     * dentro de la función. Si la validación de datos falla, devuelve un mensaje de error que indica
     * datos de usuario no válidos. Si las credenciales del usuario son correctas, devuelve un mensaje
     * de éxito junto con un token web JSON (JWT). Si las credenciales del usuario son incorrectas,
     * devuelve un mensaje de error indicando un nombre de usuario o contraseña incorrectos. Si hay un
     */
    public function login($nombreUsuario, $contrasena)
    {
        // Validamos los datos del usuario.
        if (!$this->validarDatos($nombreUsuario, $contrasena)) {
            // Datos de usuario inválidos.
            return json_encode(['status' => 'error', 'message' => 'Datos de usuario inválidos.'], JSON_UNESCAPED_UNICODE);
        }
    
        try {
            // Obtenemos los datos del usuario desde la base de datos.
            $usuario = $this->usuarioModel->obtenerUsuario($nombreUsuario);
    
            if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
                // Credenciales válidas, generamos un token JWT.
                $token = JwtHandler::encode(['id' => $usuario['id'], 'nombreUsuario' => $usuario['nombreUsuario']]);
    
                // Devolvemos una respuesta con éxito y el token.
                return json_encode(['status' => 'success', 'token' => $token], JSON_UNESCAPED_UNICODE);
            } else {
                // Credenciales incorrectas.
                return json_encode(['status' => 'error', 'message' => 'Nombre de usuario o contraseña incorrectos.'], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            // Error de base de datos.
            return json_encode(['status' => 'error', 'message' => 'Error de base de datos.'], JSON_UNESCAPED_UNICODE);
        }
    }

    private function validarDatos($nombreUsuario, $contrasena)
    {
        // Verificamos que el nombre de usuario y la contraseña no estén vacíos.
        if (empty($nombreUsuario) || empty($contrasena)) {
            return false;
        }

        // Verificamos que el nombre de usuario y la contraseña cumplan con la longitud mínima y máxima definida.
        if (strlen($nombreUsuario) < 5 || strlen($nombreUsuario) > 20 || strlen($contrasena) < 8) {
            return false;
        }

        // Verificamos que el nombre de usuario y la contraseña solo contengan caracteres permitidos.
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $nombreUsuario) || !preg_match('/^[a-zA-Z0-9!@#$%^&*()_]+$/', $contrasena)) {
            return false;
        }

        // Si todas las validaciones pasan, retornamos verdadero.
        return true;
    }
}