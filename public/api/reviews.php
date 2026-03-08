<?php
declare(strict_types=1);

// Suppress HTML error output for JSON API
ini_set('display_errors', '0');
ini_set('html_errors', '0');
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
ob_start();

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Services\BookService;
use App\Utils\Helpers;
use App\Utils\Validator;

// Require authentication
if (!Helpers::isLoggedIn()) {
    Helpers::jsonResponse(['success' => false, 'message' => 'Debes iniciar sesión para enviar una reseña.']);
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_POST['action'] ?? '';

try {
    $bookService = new BookService();

    switch ($action) {
        case 'create':
            if ($method !== 'POST') {
                throw new \Exception('Método no permitido');
            }

            $validator = new Validator($_POST);
            $validator
                ->required('id_libro', 'Libro')
                ->required('calificacion', 'Calificación')
                ->numeric('calificacion', 'Calificación')
                ->min('calificacion', 1, 'Calificación')
                ->max('calificacion', 5, 'Calificación');

            if ($validator->fails()) {
                Helpers::jsonResponse(['success' => false, 'message' => $validator->getFirstError()]);
            }

            $bookId = (int) $_POST['id_libro'];
            $userId = (int) $_SESSION['user_id'];
            $rating = (int) $_POST['calificacion'];
            $comment = trim($_POST['comentario'] ?? '');

            $reviewId = $bookService->addReview($bookId, $userId, $rating, $comment);

            Helpers::jsonResponse([
                'success' => true,
                'message' => 'Reseña publicada correctamente.',
                'id' => $reviewId
            ]);
            break;

        default:
            Helpers::jsonResponse(['success' => false, 'message' => 'Acción no válida']);
    }
} catch (\Throwable $e) {
    Helpers::jsonResponse([
        'success' => false,
        'message' => APP_ENV === 'development' ? $e->getMessage() : 'Error al procesar la reseña.'
    ]);
}
