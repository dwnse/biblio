<?php
namespace App\Repositories;

class FavoritoRepository extends BaseRepository
{
    protected string $table = 'favoritos';
    protected string $primaryKey = 'id_favorito';

    public function addFavorito(int $idUsuario, int $idLibro): bool
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO favoritos (id_usuario, id_libro) VALUES (:id_usuario, :id_libro)");
        return $stmt->execute(['id_usuario' => $idUsuario, 'id_libro' => $idLibro]);
    }

    public function removeFavorito(int $idUsuario, int $idLibro): bool
    {
        $stmt = $this->db->prepare("DELETE FROM favoritos WHERE id_usuario = :id_usuario AND id_libro = :id_libro");
        return $stmt->execute(['id_usuario' => $idUsuario, 'id_libro' => $idLibro]);
    }

    public function isFavorito(int $idUsuario, int $idLibro): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM favoritos WHERE id_usuario = :id_usuario AND id_libro = :id_libro");
        $stmt->execute(['id_usuario' => $idUsuario, 'id_libro' => $idLibro]);
        return (int) $stmt->fetch()['total'] > 0;
    }

    public function getFavoritosByUsuario(int $idUsuario): array
    {
        $sql = "SELECT l.*, e.nombre as editorial_nombre,
                       COALESCE(AVG(r.calificacion), 0) as calificacion_promedio
                FROM favoritos f
                JOIN libros l ON f.id_libro = l.id_libro
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
                LEFT JOIN resenas r ON l.id_libro = r.id_libro AND r.estado = 'visible'
                WHERE f.id_usuario = :id_usuario
                GROUP BY l.id_libro
                ORDER BY f.fecha_agregado DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_usuario' => $idUsuario]);
        $books = $stmt->fetchAll();

        $bookRepo = new BookRepository();
        foreach ($books as &$book) {
            $book['autores'] = $bookRepo->getBookAuthors((int) $book['id_libro']);
            $book['categorias'] = $bookRepo->getBookCategories((int) $book['id_libro']);
        }

        return $books;
    }
}
