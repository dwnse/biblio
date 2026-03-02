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
            <h1><?= $id ? '📝 Editar Autor' : '✨ Nuevo Autor' ?></h1>
            <p>Ingresa los detalles del autor literario.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/autores.php" class="btn btn-secondary">Volver</a>
    </div>

    <div class="card animate-fadeIn">
        <form id="authorForm" class="auth-form" style="max-width: 100%; box-shadow: none; padding: 0;">
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
                        value="<?= htmlspecialchars($author['nombre'] ?? '') ?>">
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

<script src="<?= BASE_URL ?>/js/app.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        handleForm('authorForm', '<?= BASE_URL ?>/api/catalog.php');
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
