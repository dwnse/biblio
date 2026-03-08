<?php
declare(strict_types=1);

namespace App\Repositories;

class BookRepository extends BaseRepository
{
    protected string $table = 'libros';
    protected string $primaryKey = 'id_libro';

    public function findAllWithRelations(): array
    {
        $sql = "SELECT l.*, e.nombre as editorial_nombre,
                       COALESCE(AVG(r.calificacion), 0) as calificacion_promedio
                FROM libros l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
                LEFT JOIN resenas r ON l.id_libro = r.id_libro AND r.estado = 'visible'
                GROUP BY l.id_libro
                ORDER BY l.id_libro DESC";
        $stmt = $this->db->query($sql);
        $books = $stmt->fetchAll();

        foreach ($books as &$book) {
            $book['autores'] = $this->getBookAuthors((int) $book['id_libro']);
            $book['categorias'] = $this->getBookCategories((int) $book['id_libro']);
        }
        return $books;
    }

    public function findByIdWithRelations(int $id): ?array
    {
        $sql = "SELECT l.*, e.nombre as editorial_nombre,
                       COALESCE(AVG(r.calificacion), 0) as calificacion_promedio
                FROM libros l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
                LEFT JOIN resenas r ON l.id_libro = r.id_libro AND r.estado = 'visible'
                WHERE l.id_libro = :id
                GROUP BY l.id_libro";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $book = $stmt->fetch();

        if (!$book)
            return null;

        $book['autores'] = $this->getBookAuthors($id);
        $book['categorias'] = $this->getBookCategories($id);
        $book['resenas'] = $this->getBookReviews($id);

        return $book;
    }

    public function searchBooks(string $query, ?int $categoriaId = null, int $page = 1, int $perPage = 12): array
    {
        $conditions = ["l.estado = 'disponible'"];
        $params = [];

        if (!empty($query)) {
            $conditions[] = "(l.titulo LIKE :query OR l.isbn LIKE :query2)";
            $params['query'] = "%{$query}%";
            $params['query2'] = "%{$query}%";
        }

        if ($categoriaId) {
            $conditions[] = "l.id_libro IN (SELECT id_libro FROM libro_categoria WHERE id_categoria = :cat_id)";
            $params['cat_id'] = $categoriaId;
        }

        $where = implode(' AND ', $conditions);
        $offset = ($page - 1) * $perPage;

        $countSql = "SELECT COUNT(DISTINCT l.id_libro) as total FROM libros l WHERE {$where}";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetch()['total'];

        $sql = "SELECT l.*, e.nombre as editorial_nombre,
                       COALESCE(AVG(r.calificacion), 0) as calificacion_promedio
                FROM libros l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
                LEFT JOIN resenas r ON l.id_libro = r.id_libro AND r.estado = 'visible'
                WHERE {$where}
                GROUP BY l.id_libro
                ORDER BY l.id_libro DESC
                LIMIT {$perPage} OFFSET {$offset}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $books = $stmt->fetchAll();

        foreach ($books as &$book) {
            $book['autores'] = $this->getBookAuthors((int) $book['id_libro']);
            $book['categorias'] = $this->getBookCategories((int) $book['id_libro']);
        }

        return [
            'data' => $books,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => (int) ceil($total / max($perPage, 1)),
        ];
    }

    public function getBookAuthors(int $bookId): array
    {
        $stmt = $this->db->prepare("SELECT a.* FROM autores a JOIN libro_autor la ON a.id_autor = la.id_autor WHERE la.id_libro = :id");
        $stmt->execute(['id' => $bookId]);
        return $stmt->fetchAll();
    }

    public function getBookCategories(int $bookId): array
    {
        $stmt = $this->db->prepare("SELECT c.* FROM categorias c JOIN libro_categoria lc ON c.id_categoria = lc.id_categoria WHERE lc.id_libro = :id");
        $stmt->execute(['id' => $bookId]);
        return $stmt->fetchAll();
    }

    public function getBookReviews(int $bookId): array
    {
        $sql = "SELECT r.*, u.nombre, u.apellidoP
                FROM resenas r
                JOIN usuarios u ON r.id_usuario = u.id_usuario
                WHERE r.id_libro = :id AND r.estado = 'visible'
                ORDER BY r.fecha_resena DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $bookId]);
        return $stmt->fetchAll();
    }

    public function attachAuthor(int $bookId, int $authorId): void
    {
        $stmt = $this->db->prepare("INSERT INTO libro_autor (id_libro, id_autor) VALUES (:libro, :autor)");
        $stmt->execute(['libro' => $bookId, 'autor' => $authorId]);
    }

    public function attachCategory(int $bookId, int $categoryId): void
    {
        $stmt = $this->db->prepare("INSERT INTO libro_categoria (id_libro, id_categoria) VALUES (:libro, :cat)");
        $stmt->execute(['libro' => $bookId, 'cat' => $categoryId]);
    }

    public function detachAuthors(int $bookId): void
    {
        $stmt = $this->db->prepare("DELETE FROM libro_autor WHERE id_libro = :id");
        $stmt->execute(['id' => $bookId]);
    }

    public function detachCategories(int $bookId): void
    {
        $stmt = $this->db->prepare("DELETE FROM libro_categoria WHERE id_libro = :id");
        $stmt->execute(['id' => $bookId]);
    }

    public function deleteBookReviews(int $bookId): void
    {
        $stmt = $this->db->prepare("DELETE FROM resenas WHERE id_libro = :id");
        $stmt->execute(['id' => $bookId]);
    }

    public function deleteBookDownloads(int $bookId): void
    {
        $stmt = $this->db->prepare("DELETE FROM descargas WHERE id_libro = :id");
        $stmt->execute(['id' => $bookId]);
    }

    public function deleteBookRecommendations(int $bookId): void
    {
        $stmt = $this->db->prepare("DELETE FROM recomendaciones WHERE id_libro = :id");
        $stmt->execute(['id' => $bookId]);
    }

    public function countByStatus(string $estado): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM libros WHERE estado = :estado");
        $stmt->execute(['estado' => $estado]);
        return (int) $stmt->fetch()['total'];
    }

    public function createReview(int $bookId, int $userId, int $rating, string $comment): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO resenas (id_libro, id_usuario, calificacion, comentario) VALUES (:libro, :usuario, :cal, :com)"
        );
        $stmt->execute([
            'libro' => $bookId,
            'usuario' => $userId,
            'cal' => $rating,
            'com' => $comment,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function hasUserReviewed(int $bookId, int $userId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM resenas WHERE id_libro = :libro AND id_usuario = :usuario"
        );
        $stmt->execute(['libro' => $bookId, 'usuario' => $userId]);
        return (int) $stmt->fetch()['total'] > 0;
    }
}
