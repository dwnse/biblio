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

// Configuración segura de sesiones
if (session_status() === PHP_SESSION_NONE) {
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    session_set_cookie_params([
        'lifetime' => 1800,           // 30 minutos
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isSecure,      // Solo HTTPS si está disponible
        'httponly'  => true,           // No accesible via JavaScript
        'samesite'  => 'Lax',         // Protección CSRF básica
    ]);

    session_start();

    // Control de expiración de sesión
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
        // Sesión expirada: destruir y redirigir
        session_unset();
        session_destroy();
        session_start(); // Reiniciar sesión limpia
    }
    $_SESSION['last_activity'] = time();
}

// Database configuration
// Add these constants for database connection

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'biblio');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
