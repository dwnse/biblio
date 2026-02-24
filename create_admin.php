<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/constants.php';

use App\Services\UserService;

session_start();

try {
    $userService = new UserService();

    $data = [
        'nombre' => 'Jhosmar Ricardo',
        'apellidoP' => 'Limachi',
        'apellidoM' => 'Nina',
        'email' => 'administrado@gmail.com',
        'contrasena' => 'admin123',
        'telefono' => '78827161',
        'id_rol' => 1 // 👈 Rol admin
    ];

    $userId = $userService->register($data);

    echo "✅ Admin creado correctamente. ID: " . $userId;

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}