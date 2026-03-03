<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Utils\Helpers;

http_response_code(403);
$pageTitle = 'Acceso Denegado';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="max-width: 600px; text-align: center; padding: 4rem 1rem;">
    <div class="card animate-fadeIn" style="padding: 3rem;">
        <div style="margin-bottom: 1.25rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="64" height="64" style="color: var(--danger); opacity: 0.7;">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                <line x1="15" y1="9" x2="9" y2="15" />
                <line x1="9" y1="9" x2="15" y2="15" />
            </svg>
        </div>
        <h1 style="margin-bottom: 0.5rem; color: var(--danger, #e74c3c);">403 — Acceso Denegado</h1>
        <p style="color: var(--text-muted, #6c757d); margin-bottom: 2rem;">
            No tienes permisos para acceder a esta sección.<br>
            Si crees que es un error, contacta al administrador.
        </p>
        <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
            <a href="<?= BASE_URL ?>/catalogo.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
                Ir al Catálogo
            </a>
            <?php if (!Helpers::isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/login.php" class="btn btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" y1="12" x2="3" y2="12" />
                    </svg>
                    Iniciar Sesión
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
