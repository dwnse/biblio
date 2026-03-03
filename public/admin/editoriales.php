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
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22">
                    <rect x="4" y="4" width="16" height="16" rx="2" ry="2" />
                    <rect x="9" y="9" width="6" height="6" />
                    <line x1="9" y1="1" x2="9" y2="4" />
                    <line x1="15" y1="1" x2="15" y2="4" />
                </svg>
                Editoriales
            </h1>
            <p>Gestiona las casas editoriales del catálogo.</p>
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
                                    <?php if (!empty($ed['telefono'])): ?>
                                        <span style="display:inline-flex;align-items:center;gap:0.3rem">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" style="flex-shrink:0"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                            <?= htmlspecialchars($ed['telefono']) ?>
                                        </span><br>
                                    <?php endif; ?>
                                    <?php if (!empty($ed['email'])): ?>
                                        <span style="display:inline-flex;align-items:center;gap:0.3rem">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" style="flex-shrink:0"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                            <?= htmlspecialchars($ed['email']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?= $ed['estado'] === 'activa' ? 'badge-success' : 'badge-danger' ?>">
                                        <?= ucfirst($ed['estado']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="<?= BASE_URL ?>/admin/editorial_form.php?id=<?= $ed['id_editorial'] ?>"
                                            class="btn btn-sm btn-secondary" title="Editar">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                width="15" height="15">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                        <button class="btn btn-sm btn-danger" onclick="deleteEntity(<?= $ed['id_editorial'] ?>, 'editorial')"
                                            title="Eliminar">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                width="15" height="15">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
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
