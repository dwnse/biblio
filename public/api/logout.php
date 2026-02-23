<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Services\UserService;
use App\Utils\Helpers;

$userService = new UserService();
$userService->logout();

Helpers::setFlash('success', 'Sesión cerrada correctamente.');
header('Location: ' . BASE_URL . '/login.php');
exit;
