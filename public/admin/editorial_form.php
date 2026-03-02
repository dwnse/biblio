<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utils\Helpers;
use App\Services\CatalogService;

Helpers::requireAdmin();

$catalogService = new CatalogService();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$ed = null;

if ($id) {
    $ed = $catalogService->getEditorial($id);
    if (!$ed) {
        Helpers::setFlash('error', 'Editorial no encontrada.');
        Helpers::redirect('/admin/editoriales.php');
    }
}

$pageTitle = $id ? 'Editar Editorial' : 'Nueva Editorial';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="max-width: 800px;">
    <div class="page-header">
        <div>
            <h1><?= $id ? '📝 Editar Editorial' : '🏢 Nueva Editorial' ?></h1>
            <p>Datos de contacto y ubicación de la editorial.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/editoriales.php" class="btn btn-secondary">Volver</a>
    </div>

    <div class="card animate-fadeIn">
        <form id="editorialForm" class="auth-form" style="max-width: 100%; box-shadow: none; padding: 0;">
            <input type="hidden" name="action" value="<?= $id ? 'update' : 'create' ?>">
            <input type="hidden" name="entity" value="editorial">
            <?php if ($id): ?>
                <input type="hidden" name="id" value="<?= $id ?>">
            <?php endif; ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="nombre">Nombre de la Editorial *</label>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                        placeholder="Ej: Penguin Random House" required minlength="2" maxlength="100"
                        value="<?= htmlspecialchars($ed['nombre'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="pais">País de Origen</label>
                    <input type="text" id="pais" name="pais" class="form-control"
                        placeholder="Ej: España" maxlength="100"
                        value="<?= htmlspecialchars($ed['pais'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="contacto_telefono">Teléfono de Contacto</label>
                    <input type="tel" id="contacto_telefono" name="contacto_telefono" class="form-control"
                        placeholder="+34 xxxxxxxxx" maxlength="50"
                        value="<?= htmlspecialchars($ed['contacto_telefono'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contacto_email">Correo Electrónico</label>
                    <input type="email" id="contacto_email" name="contacto_email" class="form-control"
                        placeholder="contacto@editorial.com" maxlength="150"
                        value="<?= htmlspecialchars($ed['contacto_email'] ?? '') ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label" for="sitio_web">Sitio Web Oficial</label>
                    <input type="url" id="sitio_web" name="sitio_web" class="form-control"
                        placeholder="https://..." maxlength="255"
                        value="<?= htmlspecialchars($ed['sitio_web'] ?? '') ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label" for="estado">Estado</label>
                    <select id="estado" name="estado" class="form-control">
                        <option value="activa" <?= ($ed['estado'] ?? 'activa') === 'activa' ? 'selected' : '' ?>>Activa</option>
                        <option value="inactiva" <?= ($ed['estado'] ?? '') === 'inactiva' ? 'selected' : '' ?>>Inactiva</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
                <?= $id ? 'Actualizar Editorial' : 'Guardar Editorial' ?>
            </button>
        </form>
    </div>
</div>

<script src="<?= BASE_URL ?>/js/app.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        handleForm('editorialForm', '<?= BASE_URL ?>/api/catalog.php');
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
