<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../vendor/autoload.php';
use App\Utils\Helpers;
$flash = Helpers::getFlash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Recupera tu contraseña en BiblioDigital.">
    <link rel="stylesheet" href="css/styles.css">
    <title>Recuperar Contraseña — <?= APP_NAME ?></title>
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
            <h2>¿Olvidaste tu contraseña?</h2>
            <form action="api/forgot_password.php" method="POST" style="margin-top:1.5rem;">
                <div class="form-group">
                    <label for="email">Email registrado</label>
                    <input type="email" name="email" id="email" class="form-control" required maxlength="150" placeholder="tu@email.com">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1rem;">Enviar instrucciones</button>
            </form>
        </div>
    </div>
</body>
</html>