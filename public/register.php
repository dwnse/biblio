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
    <meta name="description" content="Crea tu cuenta en BiblioDigital para descargar y explorar libros digitales.">
    <link rel="stylesheet" href="css/styles.css">

    <title>Crear Cuenta —
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

        <div class="auth-card" style="max-width: 500px;">
            <div class="auth-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="8.5" cy="7" r="4" />
                    <line x1="20" y1="8" x2="20" y2="14" />
                    <line x1="23" y1="11" x2="17" y2="11" />
                </svg>
                <h1 class="text-gradient">Crear Cuenta</h1>
                <p>Únete a nuestra comunidad digital</p>
            </div>

            <form id="registerForm" action="<?= BASE_URL ?>/api/auth.php" method="POST">
                <input type="hidden" name="action" value="register">

                <div class="form-group">
                    <label class="form-label" for="email">Correo electrónico *</label>
                    <div class="input-icon-wrapper">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <input type="email" id="email" name="email" class="form-control" placeholder="tu@email.com"
                            required maxlength="150">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="contrasena">Contraseña *</label>
                        <input type="password" id="contrasena" name="contrasena" class="form-control"
                            placeholder="Mín. 6 caracteres" required minlength="6" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contrasena_confirm">Confirmar *</label>
                        <input type="password" id="contrasena_confirm" name="contrasena_confirm" class="form-control"
                            placeholder="Repetir contraseña" required minlength="6" maxlength="255">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg mt-2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="8.5" cy="7" r="4" />
                        <line x1="20" y1="8" x2="20" y2="14" />
                        <line x1="23" y1="11" x2="17" y2="11" />
                    </svg>
                    Crear mi cuenta
                </button>
            </form>

            <div class="auth-footer">
                ¿Ya tienes cuenta? <a href="<?= BASE_URL ?>/login.php">Inicia sesión</a>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            handleForm('registerForm');
        });
    </script>
</body>

</html>