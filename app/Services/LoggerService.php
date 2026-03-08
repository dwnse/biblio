<?php
declare(strict_types=1);

namespace App\Services;

use App\Config\DatabaseConnection;
use PDO;

class LoggerService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    /**
     * Registrar una acción en el sistema
     */
    public function log(?int $userId, string $accion, string $tablaAfectada, ?int $idRegistro = null): void
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO logs (id_usuario, accion, tabla_afectada, id_registro_afectado, ip)
                 VALUES (:usuario, :accion, :tabla, :registro, :ip)"
            );
            $stmt->execute([
                'usuario' => $userId,
                'accion' => $accion,
                'tabla' => $tablaAfectada,
                'registro' => $idRegistro,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            ]);
        } catch (\PDOException $e) {
            // No interrumpir el flujo por errores de logging
            error_log("Error al registrar log: " . $e->getMessage());
        }
    }

    /**
     * Registrar una acción con formato descriptivo (usado por CatalogService)
     */
    public function logAction(int $userId, string $accion, string $descripcion, ?int $idRegistro = null): void
    {
        // Mapear la acción al nombre de tabla afectada
        $tablaMap = [
            'CREAR_AUTOR' => 'autores',
            'ACTUALIZAR_AUTOR' => 'autores',
            'ELIMINAR_AUTOR' => 'autores',
            'CREAR_CATEGORIA' => 'categorias',
            'ACTUALIZAR_CATEGORIA' => 'categorias',
            'ELIMINAR_CATEGORIA' => 'categorias',
            'CREAR_EDITORIAL' => 'editoriales',
            'ACTUALIZAR_EDITORIAL' => 'editoriales',
            'ELIMINAR_EDITORIAL' => 'editoriales',
        ];

        $tabla = $tablaMap[$accion] ?? 'general';
        $this->log($userId, "{$accion}: {$descripcion}", $tabla, $idRegistro);
    }

    /**
     * Obtener logs con filtros
     */
    public function getLogs(?int $userId = null, ?string $fechaDesde = null, ?string $fechaHasta = null, int $limit = 50): array
    {
        $conditions = ['1=1'];
        $params = [];

        if ($userId) {
            $conditions[] = 'l.id_usuario = :usuario';
            $params['usuario'] = $userId;
        }
        if ($fechaDesde) {
            $conditions[] = 'l.fecha >= :desde';
            $params['desde'] = $fechaDesde;
        }
        if ($fechaHasta) {
            $conditions[] = 'l.fecha <= :hasta';
            $params['hasta'] = $fechaHasta . ' 23:59:59';
        }

        $where = implode(' AND ', $conditions);
        $sql = "SELECT l.*, u.nombre as usuario_nombre, u.email as usuario_email
                FROM logs l
                LEFT JOIN usuarios u ON l.id_usuario = u.id_usuario
                WHERE {$where}
                ORDER BY l.fecha DESC
                LIMIT {$limit}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
