<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utils\Helpers;
use App\Services\BookService;
use App\Services\UserService;
use App\Repositories\CategoryRepository;
use App\Repositories\AuthorRepository;

Helpers::requireAdmin();

$pageTitle = 'Panel de Administración';
$bookService = new BookService();
$userService = new UserService();
$categoryRepo = new CategoryRepository();
$authorRepo = new AuthorRepository();

$bookStats = $bookService->getStats();
$userStats = $userService->getStats();
$totalCategories = $categoryRepo->count();
$totalAuthors = $authorRepo->count();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header animate-fadeIn">
        <div>
            <h1>🏠 Panel de Administración</h1>
            <p>Bienvenido,
                <?= htmlspecialchars($currentUser['name']) ?>. Aquí tienes el resumen del sistema.
            </p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid animate-fadeInUp">
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
            </div>
            <div>
                <div class="stat-value">
                    <?= $bookStats['total'] ?>
                </div>
                <div class="stat-label">Libros Totales</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
            </div>
            <div>
                <div class="stat-value">
                    <?= $userStats['total'] ?>
                </div>
                <div class="stat-label">Usuarios Registrados</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                </svg>
            </div>
            <div>
                <div class="stat-value">
                    <?= $totalCategories ?>
                </div>
                <div class="stat-label">Categorías</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </div>
            <div>
                <div class="stat-value">
                    <?= $totalAuthors ?>
                </div>
                <div class="stat-label">Autores</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card animate-fadeInUp" style="animation-delay: 0.1s;">
        <div class="card-header">
            <h3 class="card-title">Acciones Rápidas</h3>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
            <a href="<?= BASE_URL ?>/admin/libro_form.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Nuevo Libro
            </a>
            <a href="<?= BASE_URL ?>/admin/libros.php" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
                Libros
            </a>
            <a href="<?= BASE_URL ?>/admin/autores.php" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                Autores
            </a>
            <a href="<?= BASE_URL ?>/admin/categorias.php" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="14" y="14" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                </svg>
                Categorías
            </a>
            <a href="<?= BASE_URL ?>/admin/editoriales.php" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <rect x="4" y="4" width="16" height="16" rx="2" ry="2" />
                    <rect x="9" y="9" width="6" height="6" />
                    <line x1="9" y1="1" x2="9" y2="4" />
                    <line x1="15" y1="1" x2="15" y2="4" />
                </svg>
                Editoriales
            </a>
            <a href="<?= BASE_URL ?>/catalogo.php" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="14" y="14" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                </svg>
                Ver Catálogo
            </a>
        </div>
    </div>

    <!-- Detail Stats -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1.5rem;">
        <div class="card animate-fadeInUp" style="animation-delay: 0.2s;">
            <div class="card-header">
                <h3 class="card-title">📖 Estado de Libros</h3>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="text-muted">Disponibles</span>
                    <span class="badge badge-success">
                        <?= $bookStats['disponibles'] ?>
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="text-muted">No disponibles</span>
                    <span class="badge badge-danger">
                        <?= $bookStats['no_disponibles'] ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="card animate-fadeInUp" style="animation-delay: 0.3s;">
            <div class="card-header">
                <h3 class="card-title">👥 Estado de Usuarios</h3>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="text-muted">Activos</span>
                    <span class="badge badge-success">
                        <?= $userStats['activos'] ?>
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="text-muted">Inactivos</span>
                    <span class="badge badge-danger">
                        <?= $userStats['inactivos'] ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>