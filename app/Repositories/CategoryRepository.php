<?php
declare(strict_types=1);

namespace App\Repositories;

class CategoryRepository extends BaseRepository
{
    protected string $table = 'categorias';
    protected string $primaryKey = 'id_categoria';

    public function findActive(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE estado = 'activa' ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    public function toggleStatus(int $id, string $estado): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET estado = :estado WHERE id_categoria = :id");
        return $stmt->execute(['estado' => $estado, 'id' => $id]);
    }
}
