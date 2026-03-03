<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utils\Helpers;
use App\Services\BookService;

Helpers::requireAdmin();

$pageTitle = 'Gestionar Libros';
$bookService = new BookService();
$books = $bookService->getAllBooks();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header animate-fadeIn">
        <div>
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
                Libros
            </h1>
            <p>Administra el catálogo de la biblioteca digital.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/libro_form.php" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Nuevo Libro
        </a>
    </div>

    <?php if (empty($books)): ?>
        <div class="card">
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
                <h3>No hay libros registrados</h3>
                <p>Comienza agregando el primer libro al catálogo.</p>
                <a href="<?= BASE_URL ?>/admin/libro_form.php" class="btn btn-primary">Agregar primer libro</a>
            </div>
        </div>
    <?php else: ?>
        <div class="card animate-fadeIn">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor(es)</th>
                            <th>Categoría(s)</th>
                            <th>Editorial</th>
                            <th>Estado</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book):
                            $bookObj = new \App\Models\Book($book);
                            $bookObj->setAutores($book['autores'] ?? []);
                            $bookObj->setCategorias($book['categorias'] ?? []);
                            ?>
                            <tr>
                                <td>
                                    <?= $book['id_libro'] ?>
                                </td>
                                <td style="font-weight: 600; color: var(--text-primary); max-width: 200px;">
                                    <?= htmlspecialchars($book['titulo']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($bookObj->getAutoresString()) ?>
                                </td>
                                <td>
                                    <?php foreach ($book['categorias'] ?? [] as $cat): ?>
                                        <span class="badge badge-accent" style="margin: 1px;">
                                            <?= htmlspecialchars($cat['nombre']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if (empty($book['categorias'])): ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($book['editorial_nombre'] ?? '—') ?>
                                </td>
                                <td>
                                    <?php if ($book['estado'] === 'disponible'): ?>
                                        <span class="badge badge-success">Disponible</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">No disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?= $book['cantidad_disponible'] ?>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="<?= BASE_URL ?>/admin/libro_form.php?id=<?= $book['id_libro'] ?>"
                                            class="btn btn-sm btn-secondary" title="Editar">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                width="15" height="15">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('<?= BASE_URL ?>/api/books.php?action=delete&id=<?= $book['id_libro'] ?>', 'este libro')"
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>