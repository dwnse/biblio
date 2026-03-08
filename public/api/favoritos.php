<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Utils\Helpers;
use App\Services\FavoritoService;

header('Content-Type: application/json');

if (!Helpers::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para gestionar favoritos.']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$idLibro = (int) ($_POST['id_libro'] ?? $_GET['id_libro'] ?? 0);
$idUsuario = (int) Helpers::currentUser()['id'];
$favoritoService = new FavoritoService();

switch ($action) {
    case 'add':
        if (!$idLibro) {
            echo json_encode(['success' => false, 'message' => 'ID de libro inválido.']);
            exit;
        }
        $favoritoService->addFavorito($idUsuario, $idLibro);
        echo json_encode(['success' => true, 'message' => 'Libro agregado a favoritos.']);
        break;
    case 'remove':
        if (!$idLibro) {
            echo json_encode(['success' => false, 'message' => 'ID de libro inválido.']);
            exit;
        }
        $favoritoService->removeFavorito($idUsuario, $idLibro);
        echo json_encode(['success' => true, 'message' => 'Libro quitado de favoritos.']);
        break;
    case 'list':
        $favoritos = $favoritoService->getFavoritosByUsuario($idUsuario);
        echo json_encode(['success' => true, 'favoritos' => $favoritos]);
        break;
    case 'is_favorito':
        if (!$idLibro) {
            echo json_encode(['success' => false, 'message' => 'ID de libro inválido.']);
            exit;
        }
        $isFav = $favoritoService->isFavorito($idUsuario, $idLibro);
        echo json_encode(['success' => true, 'is_favorito' => $isFav]);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
}
exit;
