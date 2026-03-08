<?php
// Formulario para restablecer contraseña
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../vendor/autoload.php';
use App\Utils\Helpers;
$token = $_GET['token'] ?? '';
$flash = Helpers::getFlash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Restablece tu contraseña en BiblioDigital.">
    <link rel="stylesheet" href="css/styles.css">
    <title>Restablecer Contraseña — <?= APP_NAME ?></title>
</head>
<body>
    <?php if ($flash): ?>
        <div id="flash-data" data-type="<?= $flash['type'] ?>" data-message="<?= htmlspecialchars($flash['message']) ?>" style="display:none;"></div>
    <?php endif; ?>
    <div id="toast-container" class="toast-container"></div>
    <div class="auth-wrapper">
        <div class="auth-bg-glow glow-1"></div>
        <div class="auth-bg-glow glow-2"></div>
        <div class="auth-card">
            <a href="<?= BASE_URL ?>/catalogo.php" class="btn btn-secondary" style="float:right;margin-bottom:1rem;">← Volver al catálogo</a>
            <h2>Restablecer contraseña</h2>
            <form action="api/reset_password.php" method="POST" style="margin-top:1.5rem;">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="form-group">
                    <label for="password">Nueva contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" required minlength="6" maxlength="255" placeholder="Nueva contraseña">
                </div>
                <div class="form-group">
                    <label for="password_confirm">Confirmar contraseña</label>
                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" required minlength="6" maxlength="255" placeholder="Confirmar contraseña">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1rem;">Restablecer</button>
            </form>
        </div>
    </div>
</body>
</html>
