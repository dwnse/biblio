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
            <h1><?= $id ? '📝 Editar Categoría' : '✨ Nueva Categoría' ?></h1>
            <p>Configura las etiquetas y géneros del sistema.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/categorias.php" class="btn btn-secondary">Volver</a>
    </div>

    <div class="card animate-fadeIn">
        <form id="categoryForm" class="auth-form" style="max-width: 100%; box-shadow: none; padding: 0;">
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

<script src="<?= BASE_URL ?>/js/app.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        handleForm('categoryForm', '<?= BASE_URL ?>/api/catalog.php');
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
