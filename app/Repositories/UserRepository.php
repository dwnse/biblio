<?php
declare(strict_types=1);

namespace App\Repositories;

class UserRepository extends BaseRepository
{
    protected string $table = 'usuarios';
    protected string $primaryKey = 'id_usuario';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT u.*, r.nombre as rol_nombre FROM {$this->table} u JOIN roles r ON u.id_rol = r.id_rol WHERE u.email = :email");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return (int) $stmt->fetch()['total'] > 0;
    }

    public function updateLastAccess(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET ultimo_acceso = NOW() WHERE id_usuario = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function toggleStatus(int $id, string $estado): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET estado = :estado WHERE id_usuario = :id");
        return $stmt->execute(['estado' => $estado, 'id' => $id]);
    }

    public function findAllWithRole(): array
    {
        $stmt = $this->db->query("SELECT u.*, r.nombre as rol_nombre FROM {$this->table} u JOIN roles r ON u.id_rol = r.id_rol ORDER BY u.id_usuario DESC");
        return $stmt->fetchAll();
    }

    public function countByStatus(string $estado): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE estado = :estado");
        $stmt->execute(['estado' => $estado]);
        return (int) $stmt->fetch()['total'];
    }
}
