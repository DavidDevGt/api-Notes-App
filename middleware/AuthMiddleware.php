<?php
namespace MiProyecto\Middlewares;

use MiProyecto\Utils\JwtHandler;

class AuthMiddleware {
    private $jwtHandler;

    public function __construct(JwtHandler $jwtHandler)
    {
        $this->jwtHandler = $jwtHandler;
    }

    /**
     * La función verifica un token y devuelve la carga útil decodificada si es válida; de lo
     * contrario, devuelve un mensaje de error y sale.
     * 
     * @param token El parámetro "token" es una cadena que representa un token web JSON (JWT). Se
     * utiliza para autenticar y autorizar el acceso de un usuario a ciertos recursos o acciones en una
     * aplicación.
     * 
     * @return Si la carga útil se decodifica correctamente, será devuelta. De lo contrario, si la
     * carga útil no es válida o el token no es válido, se repetirá un mensaje de error y se cerrará el
     * script.
     */
    public function verificarToken($token) {
        $payload = $this->jwtHandler->decode($token);
        if ($payload) {
            return $payload;
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'No estás autorizado.']);
            exit();
        }
    }
}
