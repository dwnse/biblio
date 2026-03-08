<?php
declare(strict_types=1);

namespace App\Models;

class User
{
    private ?int $idUsuario;
    private int $idRol;
    private string $nombre;
    private ?string $apellidoP;
    private ?string $apellidoM;
    private string $email;
    private string $contrasena;
    private ?string $telefono;
    private string $estado;
    private ?string $fechaRegistro;
    private ?string $ultimoAcceso;

    public function __construct(array $data = [])
    {
        $this->idUsuario = isset($data['id_usuario']) ? (int) $data['id_usuario'] : null;
        $this->idRol = isset($data['id_rol']) ? (int) $data['id_rol'] : 2;
        $this->nombre = $data['nombre'] ?? '';
        $this->apellidoP = $data['apellidoP'] ?? null;
        $this->apellidoM = $data['apellidoM'] ?? null;
        $this->email = $data['email'] ?? '';
        $this->contrasena = $data['contrasena'] ?? $data['contraseña'] ?? '';
        $this->telefono = $data['telefono'] ?? null;
        $this->estado = $data['estado'] ?? 'activo';
        $this->fechaRegistro = $data['fecha_registro'] ?? null;
        $this->ultimoAcceso = $data['ultimo_acceso'] ?? null;
    }

    // Getters
    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }
    public function getIdRol(): int
    {
        return $this->idRol;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function getApellidoP(): ?string
    {
        return $this->apellidoP;
    }
    public function getApellidoM(): ?string
    {
        return $this->apellidoM;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getContrasena(): string
    {
        return $this->contrasena;
    }
    public function getTelefono(): ?string
    {
        return $this->telefono;
    }
    public function getEstado(): string
    {
        return $this->estado;
    }
    public function getFechaRegistro(): ?string
    {
        return $this->fechaRegistro;
    }
    public function getUltimoAcceso(): ?string
    {
        return $this->ultimoAcceso;
    }

    public function getNombreCompleto(): string
    {
        return trim("{$this->nombre} {$this->apellidoP} {$this->apellidoM}");
    }

    public function isAdmin(): bool
    {
        return $this->idRol === 1;
    }

    public function isActivo(): bool
    {
        return $this->estado === 'activo';
    }

    // Setters
    public function setIdUsuario(int $id): void
    {
        $this->idUsuario = $id;
    }
    public function setIdRol(int $idRol): void
    {
        $this->idRol = $idRol;
    }
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
    public function setApellidoP(?string $apellidoP): void
    {
        $this->apellidoP = $apellidoP;
    }
    public function setApellidoM(?string $apellidoM): void
    {
        $this->apellidoM = $apellidoM;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setContrasena(string $contrasena): void
    {
        $this->contrasena = $contrasena;
    }
    public function setTelefono(?string $telefono): void
    {
        $this->telefono = $telefono;
    }
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function toArray(): array
    {
        return [
            'id_usuario' => $this->idUsuario,
            'id_rol' => $this->idRol,
            'nombre' => $this->nombre,
            'apellidoP' => $this->apellidoP,
            'apellidoM' => $this->apellidoM,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'estado' => $this->estado,
            'fecha_registro' => $this->fechaRegistro,
            'ultimo_acceso' => $this->ultimoAcceso,
        ];
    }
}
