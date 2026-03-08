<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\BookRepository;
use App\Exceptions\BookNotFoundException;

class BookService
{
    private BookRepository $bookRepository;
    private LoggerService $logger;

    public function __construct()
    {
        $this->bookRepository = new BookRepository();
        $this->logger = new LoggerService();
    }

    /**
     * Obtener todos los libros con relaciones
     */
    public function getAllBooks(): array
    {
        return $this->bookRepository->findAllWithRelations();
    }

    /**
     * Obtener libro por ID con relaciones
     */
    public function getBookById(int $id): array
    {
        $book = $this->bookRepository->findByIdWithRelations($id);
        if (!$book) {
            throw new BookNotFoundException();
        }
        return $book;
    }

    /**
     * Buscar libros con filtros y paginación
     */
    public function searchBooks(string $query = '', ?int $categoriaId = null, int $page = 1, int $perPage = 12): array
    {
        return $this->bookRepository->searchBooks($query, $categoriaId, $page, $perPage);
    }

    /**
     * Crear un libro nuevo
     */
    public function createBook(array $data, array $authorIds = [], array $categoryIds = []): int
    {
        $bookData = [
            'id_editorial' => $data['id_editorial'] ?: null,
            'titulo' => $data['titulo'],
            'isbn' => $data['isbn'] ?: null,
            'descripcion' => $data['descripcion'] ?: null,
            'anio_publicacion' => $data['anio_publicacion'] ?: null,
            'portada_url' => $data['portada_url'] ?: null,
            'archivo_url' => $data['archivo_url'] ?: null,
            'cantidad_disponible' => (int) ($data['cantidad_disponible'] ?? 1),
            'estado' => $data['estado'] ?? 'disponible',
        ];

        $bookId = $this->bookRepository->create($bookData);

        // Asociar autores
        foreach ($authorIds as $authorId) {
            $this->bookRepository->attachAuthor($bookId, (int) $authorId);
        }

        // Asociar categorías
        foreach ($categoryIds as $catId) {
            $this->bookRepository->attachCategory($bookId, (int) $catId);
        }

        if (isset($_SESSION['user_id'])) {
            $this->logger->log(
                (int) $_SESSION['user_id'],
                "Creación de libro: {$data['titulo']}",
                'libros',
                $bookId
            );
        }

        return $bookId;
    }

    /**
     * Actualizar un libro
     */
    public function updateBook(int $id, array $data, array $authorIds = [], array $categoryIds = []): bool
    {
        $bookData = [
            'id_editorial' => $data['id_editorial'] ?: null,
            'titulo' => $data['titulo'],
            'isbn' => $data['isbn'] ?: null,
            'descripcion' => $data['descripcion'] ?: null,
            'anio_publicacion' => $data['anio_publicacion'] ?: null,
            'cantidad_disponible' => (int) ($data['cantidad_disponible'] ?? 1),
            'estado' => $data['estado'] ?? 'disponible',
        ];

        if (!empty($data['portada_url'])) {
            $bookData['portada_url'] = $data['portada_url'];
        }
        if (!empty($data['archivo_url'])) {
            $bookData['archivo_url'] = $data['archivo_url'];
        }

        $result = $this->bookRepository->update($id, $bookData);

        // Reestablecer relaciones
        $this->bookRepository->detachAuthors($id);
        $this->bookRepository->detachCategories($id);

        foreach ($authorIds as $authorId) {
            $this->bookRepository->attachAuthor($id, (int) $authorId);
        }
        foreach ($categoryIds as $catId) {
            $this->bookRepository->attachCategory($id, (int) $catId);
        }

        if (isset($_SESSION['user_id'])) {
            $this->logger->log(
                (int) $_SESSION['user_id'],
                "Actualización de libro ID: {$id}",
                'libros',
                $id
            );
        }

        return $result;
    }

    /**
     * Eliminar un libro
     */
    public function deleteBook(int $id): bool
    {
        // Eliminar registros dependientes antes de borrar el libro
        $this->bookRepository->deleteBookReviews($id);
        $this->bookRepository->deleteBookDownloads($id);
        $this->bookRepository->deleteBookRecommendations($id);
        $this->bookRepository->detachAuthors($id);
        $this->bookRepository->detachCategories($id);
        $result = $this->bookRepository->delete($id);

        if ($result && isset($_SESSION['user_id'])) {
            $this->logger->log(
                (int) $_SESSION['user_id'],
                "Eliminación de libro ID: {$id}",
                'libros',
                $id
            );
        }

        return $result;
    }

    /**
     * Agregar reseña a un libro
     */
    public function addReview(int $bookId, int $userId, int $rating, string $comment): int
    {
        if ($rating < 1 || $rating > 5) {
            throw new \InvalidArgumentException('La calificación debe ser entre 1 y 5.');
        }

        if ($this->bookRepository->hasUserReviewed($bookId, $userId)) {
            throw new \Exception('Ya has enviado una reseña para este libro.');
        }

        $reviewId = $this->bookRepository->createReview($bookId, $userId, $rating, $comment);

        $this->logger->log($userId, "Reseña creada para libro ID: {$bookId}", 'resenas', $reviewId);

        return $reviewId;
    }

    /**
     * Estadísticas de libros
     */
    public function getStats(): array
    {
        return [
            'total' => $this->bookRepository->count(),
            'disponibles' => $this->bookRepository->countByStatus('disponible'),
            'no_disponibles' => $this->bookRepository->countByStatus('no_disponible'),
        ];
    }
}
