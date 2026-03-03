<?php
declare(strict_types=1);

// Suppress HTML error output for JSON API
ini_set('display_errors', '0');
ini_set('html_errors', '0');
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

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

            if (!empty($_POST['portada_url'])) {
                $validator->url('portada_url', 'URL de portada')->maxLength('portada_url', 255);
            }

            if (!empty($_POST['archivo_url'])) {
                $validator->url('archivo_url', 'URL del archivo')->maxLength('archivo_url', 255);
            }

            if ($validator->fails()) {
                Helpers::jsonResponse(['success' => false, 'message' => $validator->getFirstError()]);
            }

            $authorIds = isset($_POST['autores']) ? (array) $_POST['autores'] : [];
            $categoryIds = isset($_POST['categorias']) ? (array) $_POST['categorias'] : [];

            $bookId = $bookService->createBook($_POST, $authorIds, $categoryIds);
            Helpers::setFlash('success', 'Libro registrado correctamente.');
            Helpers::jsonResponse(['success' => true, 'message' => 'Libro creado', 'id' => $bookId, 'redirect' => BASE_URL . '/admin/libros.php']);
            break;

        case 'update':
            if ($method !== 'POST')
                throw new \Exception('Método no permitido');
            $id = (int) ($_POST['id_libro'] ?? 0);
            if (!$id)
                throw new \Exception('ID de libro inválido');

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

            if (!empty($_POST['portada_url'])) {
                $validator->url('portada_url', 'URL de portada')->maxLength('portada_url', 255);
            }

            if (!empty($_POST['archivo_url'])) {
                $validator->url('archivo_url', 'URL del archivo')->maxLength('archivo_url', 255);
            }

            if ($validator->fails()) {
                Helpers::jsonResponse(['success' => false, 'message' => $validator->getFirstError()]);
            }

            $authorIds = isset($_POST['autores']) ? (array) $_POST['autores'] : [];
            $categoryIds = isset($_POST['categorias']) ? (array) $_POST['categorias'] : [];

            $bookService->updateBook($id, $_POST, $authorIds, $categoryIds);
            Helpers::setFlash('success', 'Libro actualizado correctamente.');
            Helpers::jsonResponse(['success' => true, 'message' => 'Libro actualizado', 'redirect' => BASE_URL . '/admin/libros.php']);
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
