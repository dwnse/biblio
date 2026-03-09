<?php
// Página de favoritos del usuario
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../vendor/autoload.php';
use App\Utils\Helpers;
use App\Services\FavoritoService;

Helpers::requireLogin();
$pageTitle = 'Mis Favoritos';
$favoritoService = new FavoritoService();
$userId = Helpers::currentUser()['id'];
$favoritos = $favoritoService->getFavoritosByUsuario($userId);
require_once __DIR__ . '/includes/header.php';
?>
<div class="container animate-fadeIn">
    <div class="page-header">
        <h1>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22">
                <path d="M12 21C12 21 4 13.36 4 8.5C4 5.42 6.42 3 9.5 3C11.24 3 12.91 3.81 14 5.08C15.09 3.81 16.76 3 18.5 3C21.58 3 24 5.42 24 8.5C24 13.36 16 21 16 21H12Z"/>
            </svg>
            Mis Favoritos
        </h1>
        <p>Libros que marcaste como favoritos.</p>
    </div>
    <?php if (empty($favoritos)): ?>
        <div class="empty-state" style="padding:2rem;text-align:center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="48" height="48">
                <path d="M12 21C12 21 4 13.36 4 8.5C4 5.42 6.42 3 9.5 3C11.24 3 12.91 3.81 14 5.08C15.09 3.81 16.76 3 18.5 3C21.58 3 24 5.42 24 8.5C24 13.36 16 21 16 21H12Z"/>
            </svg>
            <p>No tienes libros favoritos aún.</p>
        </div>
    <?php else: ?>
        <div class="book-grid">
            <?php foreach ($favoritos as $index => $book):
                $bookObj = new \App\Models\Book($book);
                $delay = $index * 0.05;
            ?>
                <a href="<?= BASE_URL ?>/libro.php?id=<?= $book['id_libro'] ?>" class="book-card animate-fadeInUp" style="animation-delay: <?= $delay ?>s;">
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
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
