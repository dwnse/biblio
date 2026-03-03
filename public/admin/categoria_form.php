<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utils\Helpers;
use App\Services\CatalogService;

Helpers::requireAdmin();

$catalogService = new CatalogService();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$cat = null;

if ($id) {
    $cat = $catalogService->getCategory($id);
    if (!$cat) {
        Helpers::setFlash('error', 'Categoría no encontrada.');
        Helpers::redirect('/admin/categorias.php');
    }
}

$pageTitle = $id ? 'Editar Categoría' : 'Nueva Categoría';
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
                    Editar Categoría
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Nueva Categoría
                <?php endif; ?>
            </h1>
            <p>Configura las etiquetas y géneros del catálogo.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/categorias.php" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Volver
        </a>
    </div>

    <div class="card animate-fadeIn">
        <form id="categoryForm" action="<?= BASE_URL ?>/api/catalog.php" class="auth-form" style="max-width: 100%; box-shadow: none; padding: 0;">
            <input type="hidden" name="action" value="<?= $id ? 'update' : 'create' ?>">
            <input type="hidden" name="entity" value="category">
            <?php if ($id): ?>
                <input type="hidden" name="id" value="<?= $id ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label" for="nombre">Nombre de la Categoría *</label>
                <div class="input-icon-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                        <line x1="7" y1="7" x2="7.01" y2="7" />
                    </svg>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                        placeholder="Ej: Ciencia Ficción" required minlength="2" maxlength="100"
                        value="<?= htmlspecialchars($cat['nombre'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control"
                    placeholder="Detalles sobre este género o sección..." rows="4"
                    ><?= htmlspecialchars($cat['descripcion'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="estado">Estado</label>
                <select id="estado" name="estado" class="form-control">
                    <option value="activa" <?= ($cat['estado'] ?? 'activa') === 'activa' ? 'selected' : '' ?>>Activa</option>
                    <option value="inactiva" <?= ($cat['estado'] ?? '') === 'inactiva' ? 'selected' : '' ?>>Inactiva</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
                <?= $id ? 'Actualizar Categoría' : 'Guardar Categoría' ?>
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        handleForm('categoryForm');
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
