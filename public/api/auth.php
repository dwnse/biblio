<?php
declare(strict_types=1);
require_once __DIR__ . '/../../helpers/token.php';

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

// Permitir JSON raw en el body
if ($_SERVER['CONTENT_TYPE'] ?? '' === 'application/json' || strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') === 0) {
    $rawInput = file_get_contents('php://input');
    $jsonData = json_decode($rawInput, true) ?? [];
    $_POST = array_merge($_POST, $jsonData);
}

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

            // Generar token simple
            $token = generate_simple_token($user['id_usuario'], $user['email']);

            $redirect = ((int) $user['id_rol'] === 1) ? BASE_URL . '/admin/index.php' : BASE_URL . '/catalogo.php';
            Helpers::setFlash('success', '¡Bienvenido, ' . $user['nombre'] . '!');
            Helpers::jsonResponse([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'redirect' => $redirect,
                'token' => $token
            ]);

        case 'user':
            // Obtener token del header Authorization
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
            $token = '';
            if (stripos($authHeader, 'Bearer ') === 0) {
                $token = trim(substr($authHeader, 7));
            }
            if (!$token) {
                Helpers::jsonResponse(['success' => false, 'message' => 'Token no proporcionado'], 401);
            }
            // Decodificar token simple
            $decoded = base64_decode($token);
            $parts = explode(':', $decoded);
            if (count($parts) < 2) {
                Helpers::jsonResponse(['success' => false, 'message' => 'Token inválido'], 401);
            }
            $userId = $parts[0];
            $email = $parts[1];
            // Buscar usuario por ID y email
            $userRepo = new \App\Repositories\UserRepository();
            $user = $userRepo->findByEmail($email);
            if (!$user || $user['id_usuario'] != $userId) {
                Helpers::jsonResponse(['success' => false, 'message' => 'Usuario no encontrado'], 401);
            }
            // Devolver datos básicos del usuario autenticado
            Helpers::jsonResponse([
                'success' => true,
                'user' => [
                    'id_usuario' => $user['id_usuario'],
                    'nombre' => $user['nombre'],
                    'email' => $user['email'],
                    'rol' => $user['rol_nombre'],
                    'estado' => $user['estado']
                ]
            ]);

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
