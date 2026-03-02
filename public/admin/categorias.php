<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utils\Helpers;
use App\Services\CatalogService;

Helpers::requireAdmin();

$pageTitle = 'Gestionar Categorías';
$catalogService = new CatalogService();
$categories = $catalogService->getAllCategories();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header animate-fadeIn">
        <div>
            <h1>📂 Gestionar Categorías</h1>
            <p>Administra los géneros o categorías de los libros.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/categoria_form.php" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Nueva Categoría
        </a>
    </div>

    <?php if (empty($categories)): ?>
        <div class="card">
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="14" y="14" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                </svg>
                <h3>No hay categorías registradas</h3>
                <p>Crea géneros literarios para clasificar el catálogo.</p>
                <a href="<?= BASE_URL ?>/admin/categoria_form.php" class="btn btn-primary">Agregar primera categoría</a>
            </div>
        </div>
    <?php else: ?>
        <div class="card animate-fadeIn">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de la Categoría</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><?= $cat['id_categoria'] ?></td>
                                <td><strong><?= htmlspecialchars($cat['nombre']) ?></strong></td>
                                <td><?= htmlspecialchars(substr($cat['descripcion'] ?? '-', 0, 50)) . (strlen($cat['descripcion'] ?? '') > 50 ? '...' : '') ?></td>
                                <td>
                                    <span class="badge <?= $cat['estado'] === 'activa' ? 'badge-success' : 'badge-danger' ?>">
                                        <?= ucfirst($cat['estado']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= BASE_URL ?>/admin/categoria_form.php?id=<?= $cat['id_categoria'] ?>"
                                            class="btn-icon btn-icon-primary" title="Editar">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                        <button class="btn-icon btn-icon-danger" onclick="deleteEntity(<?= $cat['id_categoria'] ?>, 'category')"
                                            title="Eliminar">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                                <line x1="10" y1="11" x2="10" y2="17" />
                                                <line x1="14" y1="11" x2="14" y2="17" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function deleteEntity(id, entity) {
        if (confirm('¿Estás seguro de eliminar esta categoría? Si tiene libros asociados, no se podrá borrar.')) {
            fetch('<?= BASE_URL ?>/api/catalog.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete&entity=${entity}&id=${id}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Ocurrió un error al intentar eliminar.');
            });
        }
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
