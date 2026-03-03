<?php
declare(strict_types=1);

// Suppress HTML error output for JSON API
ini_set('display_errors', '0');
ini_set('html_errors', '0');
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

// Start output buffering to capture any stray output
ob_start();

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Services\UserService;
use App\Utils\Validator;
use App\Utils\Helpers;

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
                ->required('email', 'Email')
                ->email('email')
                ->maxLength('email', 150, 'Email')
                ->required('contrasena', 'Contraseña')
                ->minLength('contrasena', 6, 'Contraseña')
                ->maxLength('contrasena', 255, 'Contraseña')
                ->required('contrasena_confirm', 'Confirmar contraseña')
                ->matches('contrasena', 'contrasena_confirm');

            if ($validator->fails()) {
                Helpers::jsonResponse(['success' => false, 'message' => $validator->getFirstError(), 'errors' => $validator->getErrors()]);
            }

            // Generar nombre por defecto a partir del correo
            $email = Helpers::sanitize($_POST['email']);
            $emailParts = explode('@', $email);
            $defaultName = ucfirst($emailParts[0] ?? 'Usuario');

            $data = [
                'nombre' => $defaultName,
                'apellidoP' => '',
                'apellidoM' => '',
                'email' => $email,
                'contrasena' => $_POST['contrasena'],
                'telefono' => '',
            ];

            $userId = $userService->register($data);
            Helpers::setFlash('success', '¡Registro exitoso! Ahora puedes iniciar sesión.');
            Helpers::jsonResponse(['success' => true, 'message' => 'Registro exitoso', 'redirect' => BASE_URL . '/login.php']);

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
                Helpers::jsonResponse(['success' => false, 'message' => $validator->getFirstError()]);
            }

            $user = $userService->login(
                Helpers::sanitize($_POST['email']),
                $_POST['contrasena']
            );

            $redirect = ((int) $user['id_rol'] === 1) ? BASE_URL . '/admin/index.php' : BASE_URL . '/catalogo.php';
            Helpers::setFlash('success', '¡Bienvenido, ' . $user['nombre'] . '!');
            Helpers::jsonResponse(['success' => true, 'message' => 'Inicio de sesión exitoso', 'redirect' => $redirect]);

        default:
            Helpers::jsonResponse(['success' => false, 'message' => 'Acción no válida']);
    }
} catch (\App\Exceptions\AuthenticationException $e) {
    Helpers::jsonResponse(['success' => false, 'message' => $e->getUserMessage()]);
} catch (\App\Exceptions\UserNotFoundException $e) {
    Helpers::jsonResponse(['success' => false, 'message' => $e->getUserMessage()]);
} catch (\Throwable $e) {
    Helpers::jsonResponse(['success' => false, 'message' => APP_ENV === 'development' ? $e->getMessage() : 'Error interno del servidor.']);
}
