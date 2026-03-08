<?php
// API para solicitar recuperación de contraseña
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../helpers/token.php';
use App\Repositories\UserRepository;
use App\Utils\Validator;
use App\Utils\Helpers;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$validator = new Validator(['email' => $email]);
$validator->required('email', 'Email')->email('email', 'Email');
if ($validator->fails()) {
    echo json_encode(['success' => false, 'message' => $validator->getFirstError()]);
    exit;
}

$userRepo = new UserRepository();
$user = $userRepo->findByEmail($email);
if (!$user) {
    // No revelar si el email existe o no
    echo json_encode(['success' => true, 'message' => 'Si el email está registrado, recibirás instrucciones.']);
    exit;
}
if ($user['estado'] !== 'activo') {
    echo json_encode(['success' => true, 'message' => 'Si el email está registrado, recibirás instrucciones.']);
    exit;
}

// Generar token de recuperación
$token = generate_simple_token($user['id_usuario'], $user['email']);
$expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Guardar token y expiración en la base de datos (tabla password_resets)
$db = $userRepo->getDb();
$db->prepare('DELETE FROM password_resets WHERE email = :email')->execute(['email' => $email]);
$db->prepare('INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires)')
    ->execute(['email' => $email, 'token' => $token, 'expires' => $expires]);

// Enviar email (simulado)
$resetUrl = BASE_URL . "/reset_password.php?token=" . urlencode($token);
// Aquí deberías usar una función real de envío de email
file_put_contents(__DIR__ . '/../../storage/logs/mail.log', "Recuperación para $email: $resetUrl\n", FILE_APPEND);

Helpers::setFlash('success', 'Si el email está registrado, recibirás instrucciones.');
echo json_encode(['success' => true, 'message' => 'Si el email está registrado, recibirás instrucciones.']);
exit;
