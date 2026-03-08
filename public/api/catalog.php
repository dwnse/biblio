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

use App\Services\CatalogService;
use App\Utils\Helpers;
use App\Utils\Validator;

// Check authentication - return JSON error instead of redirect for API calls
if (!Helpers::isLoggedIn()) {
    Helpers::jsonResponse([
        'success' => false, 
        'message' => 'Debes iniciar sesión para realizar esta acción.',
        'debug' => [
            'session_id' => session_id(),
            'cookies' => $_COOKIE,
            'session_data' => $_SESSION ?? 'NO_SESSION'
        ]
    ]);
}
if (!Helpers::isAdmin()) {
    Helpers::jsonResponse(['success' => false, 'message' => 'No tienes permisos para realizar esta acción.']);
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$entity = $_POST['entity'] ?? $_GET['entity'] ?? ''; // 'author', 'category', 'editorial'
$id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

try {
    $catalogService = new CatalogService();
    $userId = Helpers::currentUser()['id'];

    if ($method === 'POST') {
        $validator = new Validator($_POST);
        
        if ($action === 'create' || $action === 'update') {
            // Validaciones comunes
            $validator->required('nombre', 'Nombre')->maxLength('nombre', 100, 'Nombre');
            
            // Validaciones específicas
            if ($entity === 'author') {
                if (!empty($_POST['nacionalidad'])) {
                    $validator->maxLength('nacionalidad', 100, 'Nacionalidad');
                }
            } elseif ($entity === 'category') {
                // ... (Descripción es opcional, puede ser larga)
            } elseif ($entity === 'editorial') {
                if (!empty($_POST['pais'])) {
                    $validator->maxLength('pais', 100, 'País');
                }
                if (!empty($_POST['contacto_telefono'])) {
                    $validator->maxLength('contacto_telefono', 50, 'Teléfono');
                }
                if (!empty($_POST['contacto_email'])) {
                    $validator->email('contacto_email', 'Email')->maxLength('contacto_email', 150, 'Email');
                }
                if (!empty($_POST['sitio_web'])) {
                    $validator->url('sitio_web', 'Sitio Web')->maxLength('sitio_web', 255, 'Sitio Web');
                }
            } else {
                throw new \Exception('Entidad no válida');
            }

            if ($validator->fails()) {
                Helpers::jsonResponse(['success' => false, 'message' => $validator->getFirstError()]);
            }
        }

        switch ($action) {
            case 'create':
                if ($entity === 'author') $catalogService->createAuthor($_POST, $userId);
                elseif ($entity === 'category') $catalogService->createCategory($_POST, $userId);
                elseif ($entity === 'editorial') $catalogService->createEditorial($_POST, $userId);
                Helpers::setFlash('success', ucfirst($entity) . ' creado(a) correctamente.');
                Helpers::jsonResponse(['success' => true, 'message' => ucfirst($entity) . ' creado(a) correctamente.']);

            case 'update':
                if (!$id) throw new \Exception('ID inválido');
                if ($entity === 'author') $catalogService->updateAuthor($id, $_POST, $userId);
                elseif ($entity === 'category') $catalogService->updateCategory($id, $_POST, $userId);
                elseif ($entity === 'editorial') $catalogService->updateEditorial($id, $_POST, $userId);
                Helpers::setFlash('success', ucfirst($entity) . ' actualizado(a) correctamente.');
                Helpers::jsonResponse(['success' => true, 'message' => ucfirst($entity) . ' actualizado(a) correctamente.']);

            case 'delete':
                if (!$id) throw new \Exception('ID inválido');
                if ($entity === 'author') $catalogService->deleteAuthor($id, $userId);
                elseif ($entity === 'category') $catalogService->deleteCategory($id, $userId);
                elseif ($entity === 'editorial') $catalogService->deleteEditorial($id, $userId);
                Helpers::jsonResponse(['success' => true, 'message' => ucfirst($entity) . ' eliminado(a) correctamente.']);

            default:
                throw new \Exception('Acción no permitida');
        }
    } else {
        throw new \Exception('Método HTTP no soportado para esta API.');
    }

} catch (\Throwable $e) {
    Helpers::jsonResponse([
        'success' => false,
        'message' => APP_ENV === 'development' ? $e->getMessage() : 'Error interno del servidor.'
    ]);
}
