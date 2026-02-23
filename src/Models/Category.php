<?php
declare(strict_types=1);

namespace App\Models;

class Category
{
    private ?int $idCategoria;
    private string $nombre;
    private ?string $descripcion;
    private string $estado;
    private ?string $fechaCreacion;

    public function __construct(array $data = [])
    {
        $this->idCategoria = isset($data['id_categoria']) ? (int) $data['id_categoria'] : null;
        $this->nombre = $data['nombre'] ?? '';
        $this->descripcion = $data['descripcion'] ?? null;
        $this->estado = $data['estado'] ?? 'activa';
        $this->fechaCreacion = $data['fecha_creacion'] ?? null;
    }

    public function getIdCategoria(): ?int
    {
        return $this->idCategoria;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }
    public function getEstado(): string
    {
        return $this->estado;
    }
    public function getFechaCreacion(): ?string
    {
        return $this->fechaCreacion;
    }

    public function toArray(): array
    {
        return [
            'id_categoria' => $this->idCategoria,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'fecha_creacion' => $this->fechaCreacion,
        ];
    }
}
