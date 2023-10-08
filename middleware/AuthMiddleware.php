<?php
require_once('../utils/JwtHandler.php');

class AuthMiddleware {
    public static function verificarToken($token) {
        $payload = JwtHandler::decode($token);
        if ($payload) {
            return $payload;
        } else {
            // No autenticado
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(['status' => 'error', 'message' => 'No estás autorizado.']);
            exit();
        }
    }
}
