<?php
declare(strict_types=1);

use App\Utils\Helpers;

$flash = Helpers::getFlash();
$currentUser = Helpers::isLoggedIn() ? Helpers::currentUser() : null;
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="BiblioDigital — Plataforma de biblioteca digital para acceso, gestión y descarga de libros digitales.">
    <title>
        <?= $pageTitle ?? 'BiblioDigital' ?> —
        <?= APP_NAME ?>
    </title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>

<body>

    <?php if ($flash): ?>
        <div id="flash-data" data-type="<?= $flash['type'] ?>" data-message="<?= htmlspecialchars($flash['message']) ?>"
            style="display:none;"></div>
    <?php endif; ?>

    <div id="toast-container" class="toast-container"></div>

    <nav class="navbar">
            <a href="<?= BASE_URL ?>/catalogo.php" class="navbar-brand">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>
                </svg>
                <?= APP_NAME ?>
            </a>

            <button class="nav-toggle" aria-label="Menú">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

            <ul class="navbar-nav">

                <li>
                    <a href="<?= BASE_URL ?>/catalogo.php" class="<?= $currentPage === 'catalogo.php' ? 'active' : '' ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"/>
                            <rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/>
                        </svg>
                        Catálogo
                    </a>
                </li>
                <?php if (Helpers::isLoggedIn()): ?>
                <li>
                    <a href="<?= BASE_URL ?>/mis_favoritos.php" class="<?= $currentPage === 'mis_favoritos.php' ? 'active' : '' ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20">
                            <path d="M12 21C12 21 4 13.36 4 8.5C4 5.42 6.42 3 9.5 3C11.24 3 12.91 3.81 14 5.08C15.09 3.81 16.76 3 18.5 3C21.58 3 24 5.42 24 8.5C24 13.36 16 21 16 21H12Z"/>
                        </svg>
                        Mis Favoritos
                    </a>
                </li>
                <?php endif; ?>

                <?php if (Helpers::isAdmin()): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/admin/index.php"
                            class="<?= strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <line x1="3" y1="9" x2="21" y2="9"/>
                                <line x1="9" y1="21" x2="9" y2="9"/>
                            </svg>
                            Panel Admin
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (Helpers::isLoggedIn()): ?>
                    <li class="nav-user">
                        <div class="nav-avatar">
                            <?= strtoupper(substr($currentUser['name'], 0, 1)) ?>
                        </div>
                        <div class="nav-user-info">
                            <span class="nav-user-name">
                                <?= htmlspecialchars($currentUser['name']) ?>
                            </span>
                            <span class="nav-user-role">
                                <?= htmlspecialchars($currentUser['role_name']) ?>
                            </span>
                        </div>
                        <a href="<?= BASE_URL ?>/api/logout.php" class="btn-nav-logout">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Salir
                        </a>
                    </li>
                <?php else: ?>
                    <li style="display:flex; gap:0.5rem; margin-left: 1rem;">
                        <a href="<?= BASE_URL ?>/login.php" class="btn btn-secondary" style="padding: 0.5rem 1rem;">Entrar</a>
                        <a href="<?= BASE_URL ?>/register.php" class="btn btn-primary" style="padding: 0.5rem 1rem;">Registrarse</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

    <main class="main-content">