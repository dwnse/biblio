<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Services\BookService;
use App\Utils\Helpers;
use App\Utils\Validator;

header('Content-Type: application/json; charset=utf-8');

Helpers::requireLogin();
Helpers::requireAdmin();

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
                ->maxLength('titulo', 200, 'Título');

            if ($validator->fails()) {
                echo json_encode(['success' => false, 'message' => $validator->getFirstError()]);
                exit;
            }

            $authorIds = isset($_POST['autores']) ? (array) $_POST['autores'] : [];
            $categoryIds = isset($_POST['categorias']) ? (array) $_POST['categorias'] : [];

            $bookId = $bookService->createBook($_POST, $authorIds, $categoryIds);
            Helpers::setFlash('success', 'Libro registrado correctamente.');
            echo json_encode(['success' => true, 'message' => 'Libro creado', 'id' => $bookId, 'redirect' => BASE_URL . '/admin/libros.php']);
            break;

        case 'update':
            if ($method !== 'POST')
                throw new \Exception('Método no permitido');
            $id = (int) ($_POST['id_libro'] ?? 0);
            if (!$id)
                throw new \Exception('ID de libro inválido');

            $authorIds = isset($_POST['autores']) ? (array) $_POST['autores'] : [];
            $categoryIds = isset($_POST['categorias']) ? (array) $_POST['categorias'] : [];

            $bookService->updateBook($id, $_POST, $authorIds, $categoryIds);
            Helpers::setFlash('success', 'Libro actualizado correctamente.');
            echo json_encode(['success' => true, 'message' => 'Libro actualizado', 'redirect' => BASE_URL . '/admin/libros.php']);
            break;

        case 'delete':
            $id = (int) ($_POST['id_libro'] ?? $_GET['id'] ?? 0);
            if (!$id)
                throw new \Exception('ID de libro inválido');

            $bookService->deleteBook($id);
            Helpers::setFlash('success', 'Libro eliminado correctamente.');
            echo json_encode(['success' => true, 'message' => 'Libro eliminado']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
} catch (\App\Exceptions\BookNotFoundException $e) {
    echo json_encode(['success' => false, 'message' => $e->getUserMessage()]);
} catch (\Exception $e) {
    echo json_encode(['success' => false, 'message' => APP_ENV === 'development' ? $e->getMessage() : 'Error interno.']);
}
