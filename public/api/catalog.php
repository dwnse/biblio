<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Services\CatalogService;
use App\Utils\Helpers;
use App\Utils\Validator;

header('Content-Type: application/json; charset=utf-8');

Helpers::requireLogin();
Helpers::requireAdmin();

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
                echo json_encode(['success' => false, 'message' => $validator->getFirstError()]);
                exit;
            }
        }

        switch ($action) {
            case 'create':
                if ($entity === 'author') $catalogService->createAuthor($_POST, $userId);
                elseif ($entity === 'category') $catalogService->createCategory($_POST, $userId);
                elseif ($entity === 'editorial') $catalogService->createEditorial($_POST, $userId);
                Helpers::setFlash('success', ucfirst($entity) . ' creado(a) correctamente.');
                echo json_encode(['success' => true, 'redirect' => BASE_URL . "/admin/" . ($entity === 'category' ? 'categorias' : $entity . 'es') . ".php"]);
                break;

            case 'update':
                if (!$id) throw new \Exception('ID inválido');
                if ($entity === 'author') $catalogService->updateAuthor($id, $_POST, $userId);
                elseif ($entity === 'category') $catalogService->updateCategory($id, $_POST, $userId);
                elseif ($entity === 'editorial') $catalogService->updateEditorial($id, $_POST, $userId);
                Helpers::setFlash('success', ucfirst($entity) . ' actualizado(a) correctamente.');
                echo json_encode(['success' => true, 'redirect' => BASE_URL . "/admin/" . ($entity === 'category' ? 'categorias' : $entity . 'es') . ".php"]);
                break;

            case 'delete':
                if (!$id) throw new \Exception('ID inválido');
                if ($entity === 'author') $catalogService->deleteAuthor($id, $userId);
                elseif ($entity === 'category') $catalogService->deleteCategory($id, $userId);
                elseif ($entity === 'editorial') $catalogService->deleteEditorial($id, $userId);
                echo json_encode(['success' => true, 'message' => ucfirst($entity) . ' eliminado(a) correctamente.']);
                break;

            default:
                throw new \Exception('Acción no permitida');
        }
    } else {
        throw new \Exception('Método HTTP no soportado para esta API.');
    }

} catch (\Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
