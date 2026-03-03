<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utils\Helpers;
use App\Services\BookService;
use App\Repositories\AuthorRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\EditorialRepository;

Helpers::requireAdmin();

$bookService = new BookService();
$authorRepo = new AuthorRepository();
$categoryRepo = new CategoryRepository();
$editorialRepo = new EditorialRepository();

$authors = $authorRepo->findActive();
$categories = $categoryRepo->findActive();
$editorials = $editorialRepo->findActive();

$isEdit = false;
$book = null;
$bookAuthors = [];
$bookCategories = [];

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    try {
        $book = $bookService->getBookById($id);
        $isEdit = true;
        $bookAuthors = array_column($book['autores'] ?? [], 'id_autor');
        $bookCategories = array_column($book['categorias'] ?? [], 'id_categoria');
    } catch (\Exception $e) {
        Helpers::setFlash('error', 'Libro no encontrado.');
        Helpers::redirect('/admin/libros.php');
    }
}

$pageTitle = $isEdit ? 'Editar Libro' : 'Nuevo Libro';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container-md">
    <div class="page-header animate-fadeIn">
        <div>
            <h1>
                <?php if ($isEdit): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Editar Libro
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Nuevo Libro
                <?php endif; ?>
            </h1>
            <p>
                <?= $isEdit ? 'Modifica la información del libro.' : 'Registra un nuevo libro en el catálogo.' ?>
            </p>
        </div>
        <a href="<?= BASE_URL ?>/admin/libros.php" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Volver
        </a>
    </div>

    <div class="card-glass animate-fadeInUp">
        <form id="bookForm" action="<?= BASE_URL ?>/api/books.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'create' ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id_libro" value="<?= $book['id_libro'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label" for="titulo">Título del libro *</label>
                <div class="input-icon-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                    </svg>
                    <input type="text" id="titulo" name="titulo" class="form-control"
                        placeholder="Ej: Cien Años de Soledad" required minlength="2" maxlength="200"
                        value="<?= htmlspecialchars($book['titulo'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="isbn">ISBN</label>
                    <input type="text" id="isbn" name="isbn" class="form-control" placeholder="Ej: 978-3-16-148410-0"
                        maxlength="20" pattern="^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[a-zA-Z0-9X-]+$" title="Ingresa un formato ISBN válido (10 o 13 dígitos)"
                        value="<?= htmlspecialchars($book['isbn'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="anio_publicacion">Año de publicación</label>
                    <input type="number" id="anio_publicacion" name="anio_publicacion" class="form-control"
                        placeholder="Ej: 2024" min="1000" max="<?= date('Y') ?>" value="<?= $book['anio_publicacion'] ?? '' ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control"
                    placeholder="Describe el contenido del libro..."><?= htmlspecialchars($book['descripcion'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="id_editorial">Editorial</label>
                    <select id="id_editorial" name="id_editorial" class="form-control">
                        <option value="">Seleccionar editorial...</option>
                        <?php foreach ($editorials as $ed): ?>
                            <option value="<?= $ed['id_editorial'] ?>" <?= ($book['id_editorial'] ?? '') == $ed['id_editorial'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ed['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="cantidad_disponible">Cantidad disponible</label>
                    <input type="number" id="cantidad_disponible" name="cantidad_disponible" class="form-control"
                        min="0" value="<?= $book['cantidad_disponible'] ?? 1 ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Autores</label>
                    <select name="autores[]" class="form-control" multiple style="min-height: 100px">
                        <?php foreach ($authors as $author): ?>
                            <option value="<?= $author['id_autor'] ?>" <?= in_array($author['id_autor'], $bookAuthors) ? 'selected' : '' ?>>
                                <?= htmlspecialchars(trim($author['nombres'] . ' ' . ($author['apellidos'] ?? ''))) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form-hint">Mantén Ctrl para seleccionar varios</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Categorías</label>
                    <select name="categorias[]" class="form-control" multiple style="min-height: 100px">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id_categoria'] ?>" <?= in_array($cat['id_categoria'], $bookCategories) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form-hint">Mantén Ctrl para seleccionar varias</span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Portada del libro</label>
                    <div class="cover-upload-area" id="coverUploadArea">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 48px; height: 48px; min-width: 48px; min-height: 48px; margin: 0 auto 0.5rem auto; display: block;">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                        <p>Haz clic para seleccionar una imagen</p>
                        <p style="font-size:0.75rem; margin-top:0.3rem;">JPG, PNG o WebP — máximo 5MB</p>
                    </div>
                    <input type="file" id="portada_file" name="portada_file" accept="image/*" style="display:none;">
                    <?php if ($isEdit && !empty($book['portada_url'])): ?>
                        <div class="cover-preview" id="coverPreview">
                            <img src="<?= htmlspecialchars($book['portada_url']) ?>" alt="Portada actual">
                        </div>
                    <?php else: ?>
                        <div class="cover-preview" id="coverPreview" style="display:none;"></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Archivo PDF del libro</label>
                    <div class="cover-upload-area" id="pdfUploadArea" style="border-style: dashed; border-color: var(--primary);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 48px; height: 48px; min-width: 48px; min-height: 48px; margin: 0 auto 0.5rem auto; display: block;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="12" y1="18" x2="12" y2="12" />
                            <line x1="9" y1="15" x2="15" y2="15" />
                        </svg>
                        <p>Haz clic para subir un PDF</p>
                        <p style="font-size:0.75rem; margin-top:0.3rem;">Solo formato PDF — máximo 20MB</p>
                    </div>
                    <input type="file" id="archivo_file" name="archivo_file" accept="application/pdf" style="display:none;">
                    
                    <?php if ($isEdit && !empty($book['archivo_url'])): ?>
                        <div class="pdf-preview" id="pdfPreview" style="margin-top: 1rem; padding: 0.75rem; background: var(--bg-hover); border-radius: 6px; display: flex; align-items: center; gap: 0.5rem; color: var(--text);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" style="color: var(--primary);">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            <span style="font-size: 0.9rem; font-weight: 500;">Archivo actual cargado (<a href="<?= htmlspecialchars($book['archivo_url']) ?>" target="_blank" style="color: var(--primary);">Ver PDF</a>)</span>
                        </div>
                    <?php else: ?>
                        <div class="pdf-preview" id="pdfPreview" style="display:none; margin-top: 1rem; padding: 0.75rem; background: var(--bg-hover); border-radius: 6px; align-items: center; gap: 0.5rem; color: var(--text);">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="estado">Estado</label>
                <select id="estado" name="estado" class="form-control">
                    <option value="disponible" <?= ($book['estado'] ?? '') === 'disponible' ? 'selected' : '' ?>
                        >Disponible</option>
                    <option value="no_disponible" <?= ($book['estado'] ?? '') === 'no_disponible' ? 'selected' : '' ?>>No
                        Disponible</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary btn-lg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    <?= $isEdit ? 'Guardar Cambios' : 'Registrar Libro' ?>
                </button>
                <a href="<?= BASE_URL ?>/admin/libros.php" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
$extraScripts = '<script>document.addEventListener("DOMContentLoaded", () => { handleForm("bookForm"); });</script>';
require_once __DIR__ . '/../includes/footer.php';
?>