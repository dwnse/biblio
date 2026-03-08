<?php
declare(strict_types=1);

namespace App\Repositories;

class AuthorRepository extends BaseRepository
{
    protected string $table = 'autores';
    protected string $primaryKey = 'id_autor';

    public function findActive(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE estado = 'activo' ORDER BY nombres ASC");
        return $stmt->fetchAll();
    }

    public function toggleStatus(int $id, string $estado): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET estado = :estado WHERE id_autor = :id");
        return $stmt->execute(['estado' => $estado, 'id' => $id]);
    }
}
