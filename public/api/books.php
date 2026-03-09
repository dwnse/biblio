<?php
declare(strict_types=1);

// Suppress HTML error output for JSON API
ini_set('display_errors', '0');
ini_set('html_errors', '0');
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

if (function_exists('opcache_reset')) {
    opcache_reset();
}

// Start output buffering to capture any stray output
ob_start();

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Services\BookService;
use App\Utils\Helpers;
use App\Utils\Validator;

// Check authentication - return JSON error instead of redirect for API calls
if (!Helpers::isLoggedIn()) {
    Helpers::jsonResponse(['success' => false, 'message' => 'Debes iniciar sesión para realizar esta acción.']);
}
if (!Helpers::isAdmin()) {
    Helpers::jsonResponse(['success' => false, 'message' => 'No tienes permisos para realizar esta acción.']);
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    $bookService = new BookService();

    switch ($action) {
        case 'create':
            if ($method !== 'POST')
                throw new \Exception('Método no permitido');

            // Handle cover image upload
            if (isset($_FILES['portada_file']) && $_FILES['portada_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['portada_file'];
                $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                if (!in_array($file['type'], $allowed)) {
                    Helpers::jsonResponse(['success' => false, 'message' => 'Solo se permiten imágenes JPG, PNG, WebP o GIF.']);
                }
                if ($file['size'] > 5 * 1024 * 1024) {
                    Helpers::jsonResponse(['success' => false, 'message' => 'La imagen no debe superar los 5MB.']);
                }
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'cover_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $uploadDir = __DIR__ . '/../uploads/covers/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $destination = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $_POST['portada_url'] = '/uploads/covers/' . $filename;
                }
            }

            // Handle PDF file upload
            if (isset($_FILES['archivo_file']) && $_FILES['archivo_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['archivo_file'];
                if ($file['type'] !== 'application/pdf') {
                    Helpers::jsonResponse(['success' => false, 'message' => 'Solo se permiten archivos PDF.']);
                }
                if ($file['size'] > 20 * 1024 * 1024) {
                    Helpers::jsonResponse(['success' => false, 'message' => 'El PDF no debe superar los 20MB.']);
                }
                $filename = 'book_' . time() . '_' . bin2hex(random_bytes(4)) . '.pdf';
                $uploadDir = __DIR__ . '/../uploads/books/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $destination = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $_POST['archivo_url'] = '/uploads/books/' . $filename;
                }
            }

            $validator = new Validator($_POST);
            $validator
                ->required('titulo', 'Título')
                ->minLength('titulo', 2, 'Título')
                ->maxLength('titulo', 200, 'Título');

            if (!empty($_POST['isbn'])) {
                $validator->pattern('isbn', '/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[a-zA-Z0-9X-]+$/', 'Formato ISBN inválido. Usa 10 o 13 dígitos.');
            }

            if (!empty($_POST['anio_publicacion'])) {
                $validator->numeric('anio_publicacion', 'Año de publicación')
                          ->min('anio_publicacion', 1000, 'Año de publicación')
                          ->max('anio_publicacion', (float) date('Y'), 'Año de publicación');
            }

            if (isset($_POST['cantidad_disponible'])) {
                $validator->numeric('cantidad_disponible', 'Cantidad')
                          ->min('cantidad_disponible', 0, 'Cantidad');
            }

            if (!empty($_POST['archivo_url'])) {
                $validator->maxLength('archivo_url', 255);
            }

            if ($validator->fails()) {
                Helpers::jsonResponse(['success' => false, 'message' => $validator->getFirstError()]);
            }

            $authorIds = isset($_POST['autores']) ? (array) $_POST['autores'] : [];
            $categoryIds = isset($_POST['categorias']) ? (array) $_POST['categorias'] : [];

            $bookId = $bookService->createBook($_POST, $authorIds, $categoryIds);
            Helpers::setFlash('success', 'Libro registrado correctamente.');
            Helpers::jsonResponse(['success' => true, 'message' => 'Libro creado', 'id' => $bookId]);
            break;

        case 'update':
            if ($method !== 'POST')
                throw new \Exception('Método no permitido');
            $id = (int) ($_POST['id_libro'] ?? 0);
            if (!$id)
                throw new \Exception('ID de libro inválido');

            $validator = new Validator($_POST);

            // Handle cover image upload
            if (isset($_FILES['portada_file']) && $_FILES['portada_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['portada_file'];
                $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                if (!in_array($file['type'], $allowed)) {
                    Helpers::jsonResponse(['success' => false, 'message' => 'Solo se permiten imágenes JPG, PNG, WebP o GIF.']);
                }
                if ($file['size'] > 5 * 1024 * 1024) {
                    Helpers::jsonResponse(['success' => false, 'message' => 'La imagen no debe superar los 5MB.']);
                }
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'cover_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $uploadDir = __DIR__ . '/../uploads/covers/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $destination = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $_POST['portada_url'] = '/uploads/covers/' . $filename;
                }
            }

            // Handle PDF file upload
            if (isset($_FILES['archivo_file']) && $_FILES['archivo_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['archivo_file'];
                if ($file['type'] !== 'application/pdf') {
                    Helpers::jsonResponse(['success' => false, 'message' => 'Solo se permiten archivos PDF.']);
                }
                if ($file['size'] > 20 * 1024 * 1024) {
                    Helpers::jsonResponse(['success' => false, 'message' => 'El PDF no debe superar los 20MB.']);
                }
                $filename = 'book_' . time() . '_' . bin2hex(random_bytes(4)) . '.pdf';
                $uploadDir = __DIR__ . '/../uploads/books/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $destination = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $_POST['archivo_url'] = '/uploads/books/' . $filename;
                }
            }

            $validator
                ->required('titulo', 'Título')
                ->minLength('titulo', 2, 'Título')
                ->maxLength('titulo', 200, 'Título');

            if (!empty($_POST['isbn'])) {
                $validator->pattern('isbn', '/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[a-zA-Z0-9X-]+$/', 'Formato ISBN inválido. Usa 10 o 13 dígitos.');
            }

            if (!empty($_POST['anio_publicacion'])) {
                $validator->numeric('anio_publicacion', 'Año de publicación')
                          ->min('anio_publicacion', 1000, 'Año de publicación')
                          ->max('anio_publicacion', (float) date('Y'), 'Año de publicación');
            }

            if (isset($_POST['cantidad_disponible'])) {
                $validator->numeric('cantidad_disponible', 'Cantidad')
                          ->min('cantidad_disponible', 0, 'Cantidad');
            }

            if (!empty($_POST['archivo_url'])) {
                $validator->maxLength('archivo_url', 255);
            }

            if ($validator->fails()) {
                Helpers::jsonResponse(['success' => false, 'message' => $validator->getFirstError()]);
            }

            $authorIds = isset($_POST['autores']) ? (array) $_POST['autores'] : [];
            $categoryIds = isset($_POST['categorias']) ? (array) $_POST['categorias'] : [];

            $bookService->updateBook($id, $_POST, $authorIds, $categoryIds);
            Helpers::setFlash('success', 'Libro actualizado correctamente.');
            Helpers::jsonResponse(['success' => true, 'message' => 'Libro actualizado']);
            break;

        case 'delete':
            $id = (int) ($_POST['id_libro'] ?? $_GET['id'] ?? 0);
            if (!$id)
                throw new \Exception('ID de libro inválido');

            $bookService->deleteBook($id);
            Helpers::setFlash('success', 'Libro eliminado correctamente.');
            Helpers::jsonResponse(['success' => true, 'message' => 'Libro eliminado']);
            break;

        default:
            Helpers::jsonResponse(['success' => false, 'message' => 'Acción no válida']);
    }
} catch (\App\Exceptions\BookNotFoundException $e) {
    Helpers::jsonResponse(['success' => false, 'message' => $e->getUserMessage()]);
} catch (\Throwable $e) {
    Helpers::jsonResponse(['success' => false, 'message' => APP_ENV === 'development' ? $e->getMessage() : 'Error interno.']);
}
