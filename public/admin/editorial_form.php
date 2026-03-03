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
            <h1>
                <?php if ($id): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Editar Editorial
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Nueva Editorial
                <?php endif; ?>
            </h1>
            <p>Completa los datos de contacto y ubicación.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/editoriales.php" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Volver
        </a>
    </div>

    <div class="card animate-fadeIn">
        <form id="editorialForm" action="<?= BASE_URL ?>/api/catalog.php" class="auth-form" style="max-width: 100%; box-shadow: none; padding: 0;">
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
                        value="<?= htmlspecialchars($ed['telefono'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contacto_email">Correo Electrónico</label>
                    <input type="email" id="contacto_email" name="contacto_email" class="form-control"
                        placeholder="contacto@editorial.com" maxlength="150"
                        value="<?= htmlspecialchars($ed['email'] ?? '') ?>">
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        handleForm('editorialForm');
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
