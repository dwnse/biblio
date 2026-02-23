<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Utils\Helpers;

// Si ya tiene sesión, redirigir
if (Helpers::isLoggedIn()) {
    if (Helpers::isAdmin()) {
        Helpers::redirect('/admin/index.php');
    } else {
        Helpers::redirect('/catalogo.php');
    }
} else {
    Helpers::redirect('/login.php');
}
