<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utils\Helpers;
use App\Services\CatalogService;

Helpers::requireAdmin();

$pageTitle = 'Gestionar Editoriales';
$catalogService = new CatalogService();
$editorials = $catalogService->getAllEditorials();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header animate-fadeIn">
        <div>
            <h1>🏢 Gestionar Editoriales</h1>
            <p>Administra las casas editoriales de los libros del catálogo</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/editorial_form.php" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Nueva Editorial
        </a>
    </div>

    <?php if (empty($editorials)): ?>
        <div class="card">
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="4" y="4" width="16" height="16" rx="2" ry="2" />
                    <rect x="9" y="9" width="6" height="6" />
                    <line x1="9" y1="1" x2="9" y2="4" />
                    <line x1="15" y1="1" x2="15" y2="4" />
                    <line x1="9" y1="20" x2="9" y2="23" />
                    <line x1="15" y1="20" x2="15" y2="23" />
                    <line x1="20" y1="9" x2="23" y2="9" />
                    <line x1="20" y1="14" x2="23" y2="14" />
                    <line x1="1" y1="9" x2="4" y2="9" />
                    <line x1="1" y1="14" x2="4" y2="14" />
                </svg>
                <h3>No hay editoriales registradas</h3>
                <p>Agrega casas editoriales para mejorar el catálogo de libros.</p>
                <a href="<?= BASE_URL ?>/admin/editorial_form.php" class="btn btn-primary">Agregar primera editorial</a>
            </div>
        </div>
    <?php else: ?>
        <div class="card animate-fadeIn">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Editorial</th>
                            <th>País</th>
                            <th>Contacto (Tel / Email)</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($editorials as $ed): ?>
                            <tr>
                                <td><?= $ed['id_editorial'] ?></td>
                                <td><strong><?= htmlspecialchars($ed['nombre']) ?></strong></td>
                                <td><?= htmlspecialchars($ed['pais'] ?? '-') ?></td>
                                <td>
                                    <?php if (!empty($ed['contacto_telefono'])) echo "📞 " . htmlspecialchars($ed['contacto_telefono']) . "<br>"; ?>
                                    <?php if (!empty($ed['contacto_email'])) echo "✉️ " . htmlspecialchars($ed['contacto_email']); ?>
                                </td>
                                <td>
                                    <span class="badge <?= $ed['estado'] === 'activa' ? 'badge-success' : 'badge-danger' ?>">
                                        <?= ucfirst($ed['estado']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= BASE_URL ?>/admin/editorial_form.php?id=<?= $ed['id_editorial'] ?>"
                                            class="btn-icon btn-icon-primary" title="Editar">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                        <button class="btn-icon btn-icon-danger" onclick="deleteEntity(<?= $ed['id_editorial'] ?>, 'editorial')"
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
        if (confirm('¿Estás seguro de eliminar esta editorial? Si tiene libros asociados, no se podrá borrar.')) {
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
