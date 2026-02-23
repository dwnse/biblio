<?php
declare(strict_types=1);

namespace App\Models;

class Editorial
{
    private ?int $idEditorial;
    private string $nombre;
    private ?string $pais;
    private ?string $ciudad;
    private ?string $telefono;
    private ?string $email;
    private ?string $sitioWeb;
    private string $estado;

    public function __construct(array $data = [])
    {
        $this->idEditorial = isset($data['id_editorial']) ? (int) $data['id_editorial'] : null;
        $this->nombre = $data['nombre'] ?? '';
        $this->pais = $data['pais'] ?? null;
        $this->ciudad = $data['ciudad'] ?? null;
        $this->telefono = $data['telefono'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->sitioWeb = $data['sitio_web'] ?? null;
        $this->estado = $data['estado'] ?? 'activa';
    }

    public function getIdEditorial(): ?int
    {
        return $this->idEditorial;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function getPais(): ?string
    {
        return $this->pais;
    }
    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }
    public function getTelefono(): ?string
    {
        return $this->telefono;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function getSitioWeb(): ?string
    {
        return $this->sitioWeb;
    }
    public function getEstado(): string
    {
        return $this->estado;
    }

    public function toArray(): array
    {
        return [
            'id_editorial' => $this->idEditorial,
            'nombre' => $this->nombre,
            'pais' => $this->pais,
            'ciudad' => $this->ciudad,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'sitio_web' => $this->sitioWeb,
            'estado' => $this->estado,
        ];
    }
}
