<?php
// API para restablecer contraseña
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Repositories\UserRepository;
use App\Utils\Validator;
use App\Utils\Helpers;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$token = trim($_POST['token'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

$validator = new Validator(['password' => $password, 'password_confirm' => $password_confirm]);
$validator->required('password', 'Contraseña')->minLength('password', 6, 'Contraseña')->maxLength('password', 255, 'Contraseña');
if ($password !== $password_confirm) {
    echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden.']);
    exit;
}
if ($validator->fails()) {
    echo json_encode(['success' => false, 'message' => $validator->getFirstError()]);
    exit;
}

// Buscar token en password_resets
$db = (new UserRepository())->getDb();
$stmt = $db->prepare('SELECT * FROM password_resets WHERE token = :token');
$stmt->execute(['token' => $token]);
$row = $stmt->fetch();
if (!$row) {
    echo json_encode(['success' => false, 'message' => 'Token inválido o expirado.']);
    exit;
}
if (strtotime($row['expires_at']) < time()) {
    $db->prepare('DELETE FROM password_resets WHERE token = :token')->execute(['token' => $token]);
    echo json_encode(['success' => false, 'message' => 'El enlace ha expirado. Solicita uno nuevo.']);
    exit;
}

// Buscar usuario por email
$userRepo = new UserRepository();
$user = $userRepo->findByEmail($row['email']);
if (!$user || $user['estado'] !== 'activo') {
    echo json_encode(['success' => false, 'message' => 'No se puede restablecer la contraseña para este usuario.']);
    exit;
}

// Actualizar contraseña
$newHash = password_hash($password, PASSWORD_BCRYPT);
$db->prepare('UPDATE usuarios SET contrasena = :contrasena WHERE email = :email')
    ->execute(['contrasena' => $newHash, 'email' => $row['email']]);
// Eliminar token usado
$db->prepare('DELETE FROM password_resets WHERE token = :token')->execute(['token' => $token]);

Helpers::setFlash('success', 'Contraseña restablecida correctamente. Ahora puedes iniciar sesión.');
echo json_encode(['success' => true, 'message' => 'Contraseña restablecida correctamente.']);
exit;
