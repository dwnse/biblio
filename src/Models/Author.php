<?php
declare(strict_types=1);

namespace App\Models;

class Author
{
    private ?int $idAutor;
    private string $nombres;
    private ?string $apellidos;
    private ?string $nacionalidad;
    private ?string $fechaNacimiento;
    private ?string $biografia;
    private string $estado;

    public function __construct(array $data = [])
    {
        $this->idAutor = isset($data['id_autor']) ? (int) $data['id_autor'] : null;
        $this->nombres = $data['nombres'] ?? '';
        $this->apellidos = $data['apellidos'] ?? null;
        $this->nacionalidad = $data['nacionalidad'] ?? null;
        $this->fechaNacimiento = $data['fecha_nacimiento'] ?? null;
        $this->biografia = $data['biografia'] ?? null;
        $this->estado = $data['estado'] ?? 'activo';
    }

    public function getIdAutor(): ?int
    {
        return $this->idAutor;
    }
    public function getNombres(): string
    {
        return $this->nombres;
    }
    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }
    public function getNombreCompleto(): string
    {
        return trim("{$this->nombres} {$this->apellidos}");
    }
    public function getNacionalidad(): ?string
    {
        return $this->nacionalidad;
    }
    public function getFechaNacimiento(): ?string
    {
        return $this->fechaNacimiento;
    }
    public function getBiografia(): ?string
    {
        return $this->biografia;
    }
    public function getEstado(): string
    {
        return $this->estado;
    }

    public function toArray(): array
    {
        return [
            'id_autor' => $this->idAutor,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'nacionalidad' => $this->nacionalidad,
            'fecha_nacimiento' => $this->fechaNacimiento,
            'biografia' => $this->biografia,
            'estado' => $this->estado,
        ];
    }
}
