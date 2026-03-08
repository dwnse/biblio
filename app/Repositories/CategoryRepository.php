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

    public function existsByName(string $name, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE LOWER(nombre) = LOWER(:nombre)";
        $params = ['nombre' => $name];

        if ($excludeId !== null) {
            $sql .= " AND id_categoria != :id";
            $params['id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetch()['count'] > 0;
    }
}
