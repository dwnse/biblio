<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Services\UserService;
use App\Utils\Validator;
use App\Utils\Helpers;

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    $userService = new UserService();

    switch ($action) {
        case 'register':
            if ($method !== 'POST') {
                throw new \Exception('Método no permitido');
            }

            $validator = new Validator($_POST);
            $validator
                ->required('nombre', 'Nombre')
                ->required('email', 'Email')
                ->email('email')
                ->required('contrasena', 'Contraseña')
                ->minLength('contrasena', 6, 'Contraseña')
                ->required('contrasena_confirm', 'Confirmar contraseña')
                ->matches('contrasena', 'contrasena_confirm');

            if ($validator->fails()) {
                echo json_encode(['success' => false, 'message' => $validator->getFirstError(), 'errors' => $validator->getErrors()]);
                exit;
            }

            $data = [
                'nombre' => Helpers::sanitize($_POST['nombre']),
                'apellidoP' => Helpers::sanitize($_POST['apellidoP'] ?? ''),
                'apellidoM' => Helpers::sanitize($_POST['apellidoM'] ?? ''),
                'email' => Helpers::sanitize($_POST['email']),
                'contrasena' => $_POST['contrasena'],
                'telefono' => Helpers::sanitize($_POST['telefono'] ?? ''),
            ];

            $userId = $userService->register($data);
            Helpers::setFlash('success', '¡Registro exitoso! Ahora puedes iniciar sesión.');
            echo json_encode(['success' => true, 'message' => 'Registro exitoso', 'redirect' => BASE_URL . '/login.php']);
            break;

        case 'login':
            if ($method !== 'POST') {
                throw new \Exception('Método no permitido');
            }

            $validator = new Validator($_POST);
            $validator
                ->required('email', 'Email')
                ->email('email')
                ->required('contrasena', 'Contraseña');

            if ($validator->fails()) {
                echo json_encode(['success' => false, 'message' => $validator->getFirstError()]);
                exit;
            }

            $user = $userService->login(
                Helpers::sanitize($_POST['email']),
                $_POST['contrasena']
            );

            $redirect = ((int) $user['id_rol'] === 1) ? BASE_URL . '/admin/index.php' : BASE_URL . '/catalogo.php';
            Helpers::setFlash('success', '¡Bienvenido, ' . $user['nombre'] . '!');
            echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso', 'redirect' => $redirect]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
} catch (\App\Exceptions\AuthenticationException $e) {
    echo json_encode(['success' => false, 'message' => $e->getUserMessage()]);
} catch (\App\Exceptions\UserNotFoundException $e) {
    echo json_encode(['success' => false, 'message' => $e->getUserMessage()]);
} catch (\Exception $e) {
    echo json_encode(['success' => false, 'message' => APP_ENV === 'development' ? $e->getMessage() : 'Error interno del servidor.']);
}
