<?php
require_once('controllers/NotaController.php');
require_once('controllers/UsuarioController.php');
require_once('middleware/AuthMiddleware.php');

// Aqui miras el metodo y la ruta para saber que controlador y metodo ejecutar.
$request_method = $_SERVER['REQUEST_METHOD'];
$path_info = $_SERVER['PATH_INFO'] ?? '';

// Vamos a usar estos controladores para manejar las operaciones de la API.
$notaController = new NotaController();
$usuarioController = new UsuarioController();

switch ($path_info) {
    // Crear nota
    case '/nota/crear':
        if ($request_method == 'POST') {
            $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
            $usuario = AuthMiddleware::verificarToken($token);
            if ($usuario) {
                $titulo = $_POST['titulo'] ?? '';
                $contenido = $_POST['contenido'] ?? '';
                $idUsuario = $usuario->id ?? '';
                echo $notaController->crear($titulo, $contenido, $idUsuario);
            }
        }
        break;

    // Leer nota
    case '/nota/leer':
        if ($request_method == 'GET') {
            $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
            $usuario = AuthMiddleware::verificarToken($token);
            if ($usuario) {
                $idNota = $_GET['idNota'] ?? '';
                echo $notaController->leer($idNota);
            }
        }
        break;


    default:
        header('HTTP/1.0 404 Not Found');
        echo json_encode(['status' => 'error', 'message' => 'Ruta no encontrada.']);
        break;
}