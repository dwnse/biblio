<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Utils\Helpers;
use App\Services\BookService;
use App\Repositories\BookRepository;

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (!$id) {
    Helpers::setFlash('error', 'Libro no especificado.');
    Helpers::redirect('/catalogo.php');
}

$bookService = new BookService();

try {
    $book = $bookService->getBookById($id);
} catch (\App\Exceptions\BookNotFoundException $e) {
    Helpers::setFlash('error', $e->getUserMessage());
    Helpers::redirect('/catalogo.php');
}

$bookObj = new \App\Models\Book($book);
$bookObj->setAutores($book['autores'] ?? []);
$bookObj->setCategorias($book['categorias'] ?? []);

$pageTitle = $bookObj->getTitulo();

// Check if current user has already reviewed
$hasReviewed = false;
if (Helpers::isLoggedIn()) {
    $bookRepo = new BookRepository();
    $hasReviewed = $bookRepo->hasUserReviewed($id, (int)$_SESSION['user_id']);
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container animate-fadeIn">
    <!-- Breadcrumb -->
    <div class="mb-3" style="font-size: 0.85rem;">
        <a href="<?= BASE_URL ?>/catalogo.php" style="color: var(--text-muted);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"
                style="display:inline;vertical-align:middle;">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Volver al catálogo
        </a>
    </div>

    <!-- Book Detail -->
    <div class="book-detail">
        <div class="book-detail-cover">
            <?php if (!empty($book['portada_url'])): ?>
                <img src="<?= htmlspecialchars($book['portada_url']) ?>" alt="<?= htmlspecialchars($book['titulo']) ?>">
            <?php else: ?>
                <div class="book-cover-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                        style="width:80px;height:80px;">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                    </svg>
                    <span style="font-size:1rem;">
                        <?= htmlspecialchars($book['titulo']) ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>

        <div class="book-detail-info">

            <h1>
                <?= htmlspecialchars($book['titulo']) ?>
                <?php if (Helpers::isLoggedIn()): ?>
                    <button id="favBtn" class="btn btn-fav" style="margin-left:1rem;vertical-align:middle;" data-libro="<?= $book['id_libro'] ?>">
                        <span id="favIcon" style="color:#e74c3c;">
                            <svg id="favSvg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 21C12 21 4 13.36 4 8.5C4 5.42 6.42 3 9.5 3C11.24 3 12.91 3.81 14 5.08C15.09 3.81 16.76 3 18.5 3C21.58 3 24 5.42 24 8.5C24 13.36 16 21 16 21H12Z"/>
                            </svg>
                        </span>
                        <span id="favText">Favorito</span>
                    </button>
                <?php endif; ?>
            </h1>
<?php if (Helpers::isLoggedIn()): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const favBtn = document.getElementById('favBtn');
    if (!favBtn) return;
    const libroId = favBtn.getAttribute('data-libro');
    const favIcon = document.getElementById('favIcon');
    const favText = document.getElementById('favText');
    // Consultar si es favorito
    fetch('api/favoritos.php?action=is_favorito&id_libro=' + libroId)
        .then(r => r.json()).then(d => {
            if (d.success && d.is_favorito) {
                favBtn.classList.add('active');
                favText.textContent = 'Quitar de favoritos';
                favIcon.style.opacity = 1;
            } else {
                favBtn.classList.remove('active');
                favText.textContent = 'Favorito';
                favIcon.style.opacity = 0.5;
            }
        });
    favBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const isActive = favBtn.classList.contains('active');
        fetch('api/favoritos.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=' + (isActive ? 'remove' : 'add') + '&id_libro=' + libroId
        })
        .then(r => r.json()).then(d => {
            if (d.success) {
                if (isActive) {
                    favBtn.classList.remove('active');
                    favText.textContent = 'Favorito';
                    favIcon.style.opacity = 0.5;
                } else {
                    favBtn.classList.add('active');
                    favText.textContent = 'Quitar de favoritos';
                    favIcon.style.opacity = 1;
                }
            }
        });
    });
});
</script>
<?php endif; ?>

            <p style="color: var(--text-secondary); font-size: 1.1rem; margin-top: 0.3rem;">
                por <strong style="color: var(--accent-light);">
                    <?= htmlspecialchars($bookObj->getAutoresString()) ?>
                </strong>
            </p>

            <?php if ($book['calificacion_promedio'] > 0): ?>
                <div style="margin-top: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <?= Helpers::renderStars((float) $book['calificacion_promedio']) ?>
                    <span style="font-size: 0.9rem; color: var(--text-muted);">
                        (
                        <?= number_format((float) $book['calificacion_promedio'], 1) ?>/5)
                    </span>
                </div>
            <?php endif; ?>

            <div class="book-detail-meta">
                <?php if ($book['isbn']): ?>
                    <span class="meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                            <line x1="6" y1="8" x2="6" y2="16" />
                            <line x1="10" y1="8" x2="10" y2="16" />
                            <line x1="14" y1="8" x2="14" y2="16" />
                            <line x1="18" y1="8" x2="18" y2="16" />
                        </svg>
                        ISBN:
                        <?= htmlspecialchars($book['isbn']) ?>
                    </span>
                <?php endif; ?>
                <?php if ($book['anio_publicacion']): ?>
                    <span class="meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        Año:
                        <?= $book['anio_publicacion'] ?>
                    </span>
                <?php endif; ?>
                <?php if ($book['editorial_nombre']): ?>
                    <span class="meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        </svg>
                        <?= htmlspecialchars($book['editorial_nombre']) ?>
                    </span>
                <?php endif; ?>
                <span class="meta-item">
                    <?php if ($bookObj->isDisponible()): ?>
                        <span class="badge badge-success">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><polyline points="20 6 9 17 4 12"/></svg>
                            Disponible
                        </span>
                    <?php else: ?>
                        <span class="badge badge-danger">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            No disponible
                        </span>
                    <?php endif; ?>
                </span>
            </div>

            <!-- Categories -->
            <?php if (!empty($book['categorias'])): ?>
                <div style="display: flex; flex-wrap: wrap; gap: 0.4rem; margin: 0.75rem 0;">
                    <?php foreach ($book['categorias'] as $cat): ?>
                        <span class="badge badge-accent">
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Description -->
            <?php if ($book['descripcion']): ?>
                <div class="book-description">
                    <h3 style="margin-bottom: 0.75rem; font-size: 1rem;">Descripción</h3>
                    <?= nl2br(htmlspecialchars($book['descripcion'])) ?>
                </div>
            <?php endif; ?>

            <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
                <?php if ($bookObj->isDisponible() && !empty($book['archivo_url'])): ?>
                    <?php if (Helpers::isLoggedIn()): ?>
                        <a href="<?= htmlspecialchars($book['archivo_url']) ?>" class="btn btn-primary btn-lg" target="_blank" download>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" y1="15" x2="12" y2="3" />
                            </svg>
                            Descargar libro
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login.php" class="btn btn-primary btn-lg">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                <polyline points="10 17 15 12 10 7" />
                                <line x1="15" y1="12" x2="3" y2="12" />
                            </svg>
                            Inicia sesión para descargar
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/catalogo.php" class="btn btn-secondary btn-lg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"
                        style="display:inline;vertical-align:middle;margin-right:0.4rem;">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                    Reseñas
                </h3>
                <span class="badge badge-info">
                    <?= count($book['resenas'] ?? []) ?> reseñas
                </span>
            </div>

            <?php if (empty($book['resenas'])): ?>
                <div class="empty-state" style="padding: 2rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                    <p>Aún no hay reseñas para este libro.</p>
                </div>
            <?php else: ?>
                <?php foreach ($book['resenas'] as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-avatar">
                                <?= strtoupper(substr($review['nombre'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="review-author">
                                    <?= htmlspecialchars($review['nombre'] . ' ' . ($review['apellidoP'] ?? '')) ?>
                                </div>
                                <div class="review-date">
                                    <?= Helpers::formatDate($review['fecha_resena'], 'd M Y') ?>
                                </div>
                            </div>
                            <div style="margin-left: auto;">
                                <?= Helpers::renderStars((float) $review['calificacion']) ?>
                            </div>
                        </div>
                        <?php if ($review['comentario']): ?>
                            <div class="review-text">
                                <?= nl2br(htmlspecialchars($review['comentario'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Review Form -->
        <?php if (Helpers::isLoggedIn()): ?>
            <?php if (!$hasReviewed): ?>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"
                                style="display:inline;vertical-align:middle;margin-right:0.4rem;">
                                <path d="M12 20h9" />
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                            </svg>
                            Escribe tu reseña
                        </h3>
                    </div>
                    <div style="padding: 1.25rem;">
                        <form id="reviewForm" action="<?= BASE_URL ?>/api/reviews.php" method="POST">
                            <input type="hidden" name="action" value="create">
                            <input type="hidden" name="id_libro" value="<?= $book['id_libro'] ?>">
                            <input type="hidden" name="calificacion" id="reviewRatingInput" value="0">

                            <div class="form-group">
                                <label class="form-label">Tu calificación *</label>
                                <div class="star-rating-input" id="starRatingPicker">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg class="star-pick" data-value="<?= $i ?>" viewBox="0 0 24 24" width="32" height="32"
                                            stroke="currentColor" stroke-width="1.5" fill="none">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    <?php endfor; ?>
                                    <span class="star-rating-label" id="starRatingLabel">Selecciona una calificación</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="comentario">Comentario (opcional)</label>
                                <textarea id="comentario" name="comentario" class="form-control" rows="3"
                                    placeholder="Comparte tu opinión sobre este libro..." maxlength="1000"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary" id="submitReviewBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                    <line x1="22" y1="2" x2="11" y2="13" />
                                    <polygon points="22 2 15 22 11 13 2 9 22 2" />
                                </svg>
                                Publicar reseña
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="card" style="margin-top: 1rem;">
                    <div style="padding: 1.25rem; text-align: center; color: var(--text-muted);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"
                            style="display:inline;vertical-align:middle;margin-right:0.3rem;">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Ya has enviado tu reseña para este libro.
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="card" style="margin-top: 1rem;">
                <div style="padding: 1.25rem; text-align: center;">
                    <a href="<?= BASE_URL ?>/login.php" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                            <polyline points="10 17 15 12 10 7" />
                            <line x1="15" y1="12" x2="3" y2="12" />
                        </svg>
                        Inicia sesión para dejar una reseña
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>