<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utils\Helpers;
use App\Services\CatalogService;

Helpers::requireAdmin();

$pageTitle = 'Gestionar Autores';
$catalogService = new CatalogService();
$authors = $catalogService->getAllAuthors();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header animate-fadeIn">
        <div>
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                Autores
            </h1>
            <p>Gestiona los autores del catálogo literario.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/autor_form.php" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Nuevo Autor
        </a>
    </div>

    <?php if (empty($authors)): ?>
        <div class="card">
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <h3>No hay autores registrados</h3>
                <p>Comienza agregando el primer autor al sistema.</p>
                <a href="<?= BASE_URL ?>/admin/autor_form.php" class="btn btn-primary">Agregar primer autor</a>
            </div>
        </div>
    <?php else: ?>
        <div class="card animate-fadeIn">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Nacionalidad</th>
                            <th>Nacimiento</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($authors as $author): ?>
                            <tr>
                                <td><?= $author['id_autor'] ?></td>
                                <td><strong><?= htmlspecialchars($author['nombres']) ?></strong></td>
                                <td><?= htmlspecialchars($author['nacionalidad'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($author['fecha_nacimiento'] ?? '-') ?></td>
                                <td>
                                    <span class="badge <?= $author['estado'] === 'activo' ? 'badge-success' : 'badge-danger' ?>">
                                        <?= ucfirst($author['estado']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="<?= BASE_URL ?>/admin/autor_form.php?id=<?= $author['id_autor'] ?>"
                                            class="btn btn-sm btn-secondary" title="Editar">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                width="15" height="15">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                        <button class="btn btn-sm btn-danger" onclick="deleteEntity(<?= $author['id_autor'] ?>, 'author')"
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
        if (confirm('¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.')) {
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
