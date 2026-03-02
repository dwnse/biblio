<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Utils\Helpers;

// Redirigir según el rol o al catálogo público si es invitado
if (Helpers::isLoggedIn() && Helpers::isAdmin()) {
    Helpers::redirect('/admin/index.php');
} else {
    Helpers::redirect('/catalogo.php');
}
