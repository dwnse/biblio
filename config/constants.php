<?php
declare(strict_types=1);

// Configuración general de la aplicación
define('APP_NAME', 'BiblioDigital');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development');

// Rutas del sistema
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('SRC_PATH', ROOT_PATH . '/src');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');

// URL base (ajustar según entorno)
define('BASE_URL', '/biblio/public');

// Zona horaria
date_default_timezone_set('America/La_Paz');

// Configuración de errores según entorno
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
