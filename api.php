<?php
require_once __DIR__ . '/vendor/autoload.php';

use MiProyecto\Controllers\NotaController;
use MiProyecto\Controllers\UsuarioController;
use MiProyecto\Middleware\AuthMiddleware;

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

    // Actualizar nota
    case '/nota/actualizar':
        if ($request_method == 'POST') {
            $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
            $usuario = AuthMiddleware::verificarToken($token);
            if ($usuario) {
                $idNota = $_POST['idNota'] ?? '';
                $nuevoTitulo = $_POST['nuevoTitulo'] ?? '';
                $nuevoContenido = $_POST['nuevoContenido'] ?? '';
                echo $notaController->actualizar($idNota, $nuevoTitulo, $nuevoContenido);
            }
        }
        break;

    // Eliminar nota
    case '/nota/eliminar':
        if ($request_method == 'DELETE') {
            $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
            $usuario = AuthMiddleware::verificarToken($token);
            if ($usuario) {
                $idNota = $_GET['idNota'] ?? '';
                echo $notaController->eliminar($idNota);
            }
        }
        break;

    // Crear usuario
    case '/usuario/crear':
        if ($request_method == 'POST') {
            $nombreUsuario = $_POST['nombreUsuario'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';
            echo $usuarioController->crear($nombreUsuario, $contrasena);
        }
        break;

    // Obtener usuario
    case '/usuario/obtener':
        if ($request_method == 'GET') {
            $nombreUsuario = $_GET['nombreUsuario'] ?? '';
            echo $usuarioController->obtener($nombreUsuario);
        }
        break;

    // Actualizar usuario
    case '/usuario/actualizar':
        if ($request_method == 'POST') {
            $nombreUsuario = $_POST['nombreUsuario'] ?? '';
            $nuevaContrasena = $_POST['nuevaContrasena'] ?? '';
            echo $usuarioController->actualizar($nombreUsuario, $nuevaContrasena);
        }
        break;

    // Eliminar usuario
    case '/usuario/eliminar':
        if ($request_method == 'DELETE') {
            $nombreUsuario = $_GET['nombreUsuario'] ?? '';
            echo $usuarioController->eliminar($nombreUsuario);
        }
        break;

    // Login
    case '/usuario/login':
        if ($request_method == 'POST') {
            $nombreUsuario = $_POST['nombreUsuario'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';
            echo $usuarioController->login($nombreUsuario, $contrasena);
        }
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        echo json_encode(['status' => 'error', 'message' => 'Ruta no encontrada.']);
        break;
}