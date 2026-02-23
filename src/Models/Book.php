<?php
declare(strict_types=1);

namespace App\Models;

class Book
{
    private ?int $idLibro;
    private ?int $idEditorial;
    private string $titulo;
    private ?string $isbn;
    private ?string $descripcion;
    private ?int $anioPublicacion;
    private ?string $portadaUrl;
    private ?string $archivoUrl;
    private int $cantidadDisponible;
    private string $estado;
    private ?string $fechaRegistro;

    // Relaciones cargadas
    private ?string $editorialNombre;
    private array $autores;
    private array $categorias;
    private ?float $calificacionPromedio;

    public function __construct(array $data = [])
    {
        $this->idLibro = isset($data['id_libro']) ? (int) $data['id_libro'] : null;
        $this->idEditorial = isset($data['id_editorial']) ? (int) $data['id_editorial'] : null;
        $this->titulo = $data['titulo'] ?? '';
        $this->isbn = $data['isbn'] ?? null;
        $this->descripcion = $data['descripcion'] ?? null;
        $this->anioPublicacion = isset($data['anio_publicacion']) ? (int) $data['anio_publicacion'] : null;
        $this->portadaUrl = $data['portada_url'] ?? null;
        $this->archivoUrl = $data['archivo_url'] ?? null;
        $this->cantidadDisponible = isset($data['cantidad_disponible']) ? (int) $data['cantidad_disponible'] : 0;
        $this->estado = $data['estado'] ?? 'disponible';
        $this->fechaRegistro = $data['fecha_registro'] ?? null;
        $this->editorialNombre = $data['editorial_nombre'] ?? null;
        $this->autores = [];
        $this->categorias = [];
        $this->calificacionPromedio = isset($data['calificacion_promedio']) ? (float) $data['calificacion_promedio'] : null;
    }

    // Getters
    public function getIdLibro(): ?int
    {
        return $this->idLibro;
    }
    public function getIdEditorial(): ?int
    {
        return $this->idEditorial;
    }
    public function getTitulo(): string
    {
        return $this->titulo;
    }
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }
    public function getAnioPublicacion(): ?int
    {
        return $this->anioPublicacion;
    }
    public function getPortadaUrl(): ?string
    {
        return $this->portadaUrl;
    }
    public function getArchivoUrl(): ?string
    {
        return $this->archivoUrl;
    }
    public function getCantidadDisponible(): int
    {
        return $this->cantidadDisponible;
    }
    public function getEstado(): string
    {
        return $this->estado;
    }
    public function getFechaRegistro(): ?string
    {
        return $this->fechaRegistro;
    }
    public function getEditorialNombre(): ?string
    {
        return $this->editorialNombre;
    }
    public function getAutores(): array
    {
        return $this->autores;
    }
    public function getCategorias(): array
    {
        return $this->categorias;
    }
    public function getCalificacionPromedio(): ?float
    {
        return $this->calificacionPromedio;
    }

    public function isDisponible(): bool
    {
        return $this->estado === 'disponible' && $this->cantidadDisponible > 0;
    }

    public function getAutoresString(): string
    {
        if (empty($this->autores))
            return 'Sin autor';
        return implode(', ', array_map(fn($a) => trim(($a['nombres'] ?? '') . ' ' . ($a['apellidos'] ?? '')), $this->autores));
    }

    public function getCategoriasString(): string
    {
        if (empty($this->categorias))
            return 'Sin categoría';
        return implode(', ', array_map(fn($c) => $c['nombre'] ?? '', $this->categorias));
    }

    // Setters
    public function setIdLibro(int $id): void
    {
        $this->idLibro = $id;
    }
    public function setAutores(array $autores): void
    {
        $this->autores = $autores;
    }
    public function setCategorias(array $categorias): void
    {
        $this->categorias = $categorias;
    }
    public function setCalificacionPromedio(?float $cal): void
    {
        $this->calificacionPromedio = $cal;
    }

    public function toArray(): array
    {
        return [
            'id_libro' => $this->idLibro,
            'id_editorial' => $this->idEditorial,
            'titulo' => $this->titulo,
            'isbn' => $this->isbn,
            'descripcion' => $this->descripcion,
            'anio_publicacion' => $this->anioPublicacion,
            'portada_url' => $this->portadaUrl,
            'archivo_url' => $this->archivoUrl,
            'cantidad_disponible' => $this->cantidadDisponible,
            'estado' => $this->estado,
            'fecha_registro' => $this->fechaRegistro,
            'editorial_nombre' => $this->editorialNombre,
            'autores' => $this->getAutoresString(),
            'categorias' => $this->getCategoriasString(),
        ];
    }
}
