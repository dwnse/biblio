<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Utils\Helpers;
use App\Services\BookService;
use App\Repositories\CategoryRepository;

$pageTitle = 'Catálogo';
$bookService = new BookService();
$categoryRepo = new CategoryRepository();

$query = $_GET['q'] ?? '';
$catFilter = isset($_GET['categoria']) ? (int) $_GET['categoria'] : null;
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

$result = $bookService->searchBooks($query, $catFilter, $page, 12);
$books = $result['data'];
$totalPages = $result['totalPages'];
$categories = $categoryRepo->findActive();

require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="page-header animate-fadeIn">
        <div>
            <h1>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
                Catálogo de Libros
            </h1>
            <p>Explora nuestra colección de libros digitales.</p>
        </div>
    </div>

    <!-- Search & Filters -->
    <form class="search-bar animate-fadeIn" method="GET" action="">
        <div class="search-input-wrapper">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="text" name="q" value="<?= htmlspecialchars($query) ?>"
                placeholder="¿Qué libro buscas?">
        </div>
        <div class="search-filter">
            <select name="categoria" onchange="this.form.submit()">
                <option value="">Todas las categorías</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= $catFilter == $cat['id_categoria'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            Buscar
        </button>
    </form>

    <?php if (empty($books)): ?>
        <div class="empty-state animate-fadeIn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
            </svg>
            <h3>No se encontraron libros</h3>
            <p>Intenta con otros términos de búsqueda o cambia los filtros.</p>
            <?php if ($query || $catFilter): ?>
                <a href="<?= BASE_URL ?>/catalogo.php" class="btn btn-secondary">Ver todos los libros</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php if (empty($query) && empty($catFilter) && $page === 1): ?>
            <?php $topBooks = $bookService->getMostDownloadedBooks(4); ?>
            <?php if (!empty($topBooks)): ?>
                <div style="margin-bottom: 3rem;">
                    <br>
                    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" width="24" height="24">
                            <path d="M12 15V3m0 12l-4-4m4 4l4-4M2 17l.621 2.485A2 2 0 0 0 4.561 21h14.878a2 2 0 0 0 1.94-1.515L22 17"></path>
                        </svg>
                        Los libros más descargados
                    </h2>
                    <div class="book-grid">
                        <?php foreach ($topBooks as $index => $book):
                            $bookObj = new \App\Models\Book($book);
                            $delay = $index * 0.05;
                            ?>
                            <a href="<?= BASE_URL ?>/libro.php?id=<?= $book['id_libro'] ?>" class="book-card animate-fadeInUp"
                                style="animation-delay: <?= $delay ?>s;">
                                <div class="book-cover">
                                    <?php if (!empty($book['portada_url'])): ?>
                                        <img src="<?= BASE_URL . htmlspecialchars($book['portada_url']) ?>" alt="<?= htmlspecialchars($book['titulo']) ?>">
                                    <?php else: ?>
                                        <div class="book-cover-placeholder">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                                            </svg>
                                            <span>
                                                <?= htmlspecialchars(mb_substr($book['titulo'], 0, 25)) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($bookObj->isDisponible()): ?>
                                        <span class="book-status-badge badge badge-success">Disponible</span>
                                    <?php else: ?>
                                        <span class="book-status-badge badge badge-danger">No disponible</span>
                                    <?php endif; ?>
                                </div>
                                <div class="book-info">
                                    <div class="book-title">
                                        <?= htmlspecialchars($book['titulo']) ?>
                                    </div>
                                    <div class="book-author">
                                        <?= htmlspecialchars($bookObj->getAutoresString()) ?>
                                    </div>
                                    <div class="book-meta">
                                        <span class="book-category">
                                            <?= htmlspecialchars($bookObj->getCategoriasString()) ?>
                                        </span>
                                        <?php if ($book['calificacion_promedio'] > 0): ?>
                                            <?= Helpers::renderStars((float) $book['calificacion_promedio']) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                    </svg>
                    Catálogo Completo
                </h2>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="book-grid">
            <?php foreach ($books as $index => $book):
                $bookObj = new \App\Models\Book($book);
                $delay = $index * 0.05;
                ?>
                <a href="<?= BASE_URL ?>/libro.php?id=<?= $book['id_libro'] ?>" class="book-card animate-fadeInUp"
                    style="animation-delay: <?= $delay ?>s;">
                    <div class="book-cover">
                        <?php if (!empty($book['portada_url'])): ?>
                            <img src="<?= htmlspecialchars($book['portada_url']) ?>" alt="<?= htmlspecialchars($book['titulo']) ?>">
                        <?php else: ?>
                            <div class="book-cover-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                                </svg>
                                <span>
                                    <?= htmlspecialchars(mb_substr($book['titulo'], 0, 25)) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php if ($bookObj->isDisponible()): ?>
                            <span class="book-status-badge badge badge-success">Disponible</span>
                        <?php else: ?>
                            <span class="book-status-badge badge badge-danger">No disponible</span>
                        <?php endif; ?>
                    </div>
                    <div class="book-info">
                        <div class="book-title">
                            <?= htmlspecialchars($book['titulo']) ?>
                        </div>
                        <div class="book-author">
                            <?= htmlspecialchars($bookObj->getAutoresString()) ?>
                        </div>
                        <div class="book-meta">
                            <span class="book-category">
                                <?= htmlspecialchars($bookObj->getCategoriasString()) ?>
                            </span>
                            <?php if ($book['calificacion_promedio'] > 0): ?>
                                <?= Helpers::renderStars((float) $book['calificacion_promedio']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                    </a>
                <?php endif; ?>

                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                    <?php if ($i === $page): ?>
                        <span class="active">
                            <?= $i ?>
                        </span>
                    <?php else: ?>
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>