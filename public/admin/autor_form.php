<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utils\Helpers;
use App\Services\CatalogService;

Helpers::requireAdmin();

$catalogService = new CatalogService();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$author = null;

if ($id) {
    $author = $catalogService->getAuthor($id);
    if (!$author) {
        Helpers::setFlash('error', 'Autor no encontrado.');
        Helpers::redirect('/admin/autores.php');
    }
}

$pageTitle = $id ? 'Editar Autor' : 'Nuevo Autor';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="max-width: 800px;">
    <div class="page-header">
        <div>
            <h1>
                <?php if ($id): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Editar Autor
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Nuevo Autor
                <?php endif; ?>
            </h1>
            <p>Completa los datos del autor literario.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/autores.php" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Volver
        </a>
    </div>

    <div class="card animate-fadeIn">
        <form id="authorForm" action="<?= BASE_URL ?>/api/catalog.php" class="auth-form" style="max-width: 100%; box-shadow: none; padding: 0;">
            <input type="hidden" name="action" value="<?= $id ? 'update' : 'create' ?>">
            <input type="hidden" name="entity" value="author">
            <?php if ($id): ?>
                <input type="hidden" name="id" value="<?= $id ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label" for="nombre">Nombre Completo *</label>
                <div class="input-icon-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                        placeholder="Ej: Gabriel García Márquez" required minlength="2" maxlength="100"
                        value="<?= htmlspecialchars($author['nombres'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="nacionalidad">Nacionalidad</label>
                    <input type="text" id="nacionalidad" name="nacionalidad" class="form-control"
                        placeholder="Ej: Colombiano" maxlength="100"
                        value="<?= htmlspecialchars($author['nacionalidad'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control"
                        max="<?= date('Y-m-d') ?>" value="<?= $author['fecha_nacimiento'] ?? '' ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="estado">Estado</label>
                <select id="estado" name="estado" class="form-control">
                    <option value="activo" <?= ($author['estado'] ?? 'activo') === 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="inactivo" <?= ($author['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
                <?= $id ? 'Actualizar Autor' : 'Guardar Autor' ?>
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        handleForm('authorForm');
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
