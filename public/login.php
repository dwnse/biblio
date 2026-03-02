<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Utils\Helpers;

if (Helpers::isLoggedIn()) {
    Helpers::redirect('/catalogo.php');
}

$flash = Helpers::getFlash();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Inicia sesión en BiblioDigital para acceder al catálogo de libros digitales.">
    <link rel="stylesheet" href="css/styles.css">
    <title>Iniciar Sesión —
        <?= APP_NAME ?>
    </title>
</head>

<body>

    <?php if ($flash): ?>
        <div id="flash-data" data-type="<?= $flash['type'] ?>" data-message="<?= htmlspecialchars($flash['message']) ?>"
            style="display:none;"></div>
    <?php endif; ?>
    <div id="toast-container" class="toast-container"></div>

    <div class="auth-wrapper">
        <div class="auth-bg-glow glow-1"></div>
        <div class="auth-bg-glow glow-2"></div>

        <div class="auth-card">
            <div class="auth-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
                <h1 class="text-gradient">BiblioDigital</h1>
                <p>Accede a tu biblioteca personal</p>
            </div>

            <form id="loginForm" action="<?= BASE_URL ?>/api/auth.php" method="POST">
                <input type="hidden" name="action" value="login">

                <div class="form-group">
                    <label class="form-label" for="email">Correo electrónico</label>
                    <div class="input-icon-wrapper">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <input type="email" id="email" name="email" class="form-control" placeholder="tu@email.com"
                            required autocomplete="email" maxlength="150">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="contrasena">Contraseña</label>
                    <div class="input-icon-wrapper">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                        <input type="password" id="contrasena" name="contrasena" class="form-control"
                            placeholder="••••••••" required minlength="6" maxlength="255" autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg mt-2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" y1="12" x2="3" y2="12" />
                    </svg>
                    Iniciar Sesión
                </button>
            </form>

            <div class="auth-footer">
                ¿No tienes cuenta? <a href="<?= BASE_URL ?>/register.php">Regístrate aquí</a>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            handleForm('loginForm');
        });
    </script>
</body>

</html>