<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\AuthorRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\EditorialRepository;
use Exception;

class CatalogService
{
    private AuthorRepository $authorRepo;
    private CategoryRepository $catRepo;
    private EditorialRepository $editRepo;
    private LoggerService $logger;

    public function __construct()
    {
        $this->authorRepo = new AuthorRepository();
        $this->catRepo = new CategoryRepository();
        $this->editRepo = new EditorialRepository();
        $this->logger = new LoggerService();
    }

    // --- AUTORES ---
    public function getAllAuthors(): array
    {
        return $this->authorRepo->findAll();
    }

    public function getAuthor(int $id): ?array
    {
        return $this->authorRepo->findById($id);
    }

    public function createAuthor(array $data, int $userId): int
    {
        $id = $this->authorRepo->create([
            'nombres' => $data['nombre'],
            'nacionalidad' => $data['nacionalidad'] ?? null,
            'fecha_nacimiento' => !empty($data['fecha_nacimiento']) ? $data['fecha_nacimiento'] : null,
            'estado' => $data['estado'] ?? 'activo'
        ]);

        $this->logger->logAction($userId, 'CREAR_AUTOR', "Autor creado ID: $id", $id);
        return $id;
    }

    public function updateAuthor(int $id, array $data, int $userId): void
    {
        $this->authorRepo->update($id, [
            'nombres' => $data['nombre'],
            'nacionalidad' => $data['nacionalidad'] ?? null,
            'fecha_nacimiento' => !empty($data['fecha_nacimiento']) ? $data['fecha_nacimiento'] : null,
            'estado' => $data['estado'] ?? 'activo'
        ]);

        $this->logger->logAction($userId, 'ACTUALIZAR_AUTOR', "Autor actualizado ID: $id", $id);
    }

    public function deleteAuthor(int $id, int $userId): void
    {
        // En un sistema real se debería validar si el autor tiene libros asignados antes de borrar
        try {
            $this->authorRepo->delete($id);
            $this->logger->logAction($userId, 'ELIMINAR_AUTOR', "Autor eliminado ID: $id", $id);
        } catch (Exception $e) {
            throw new Exception("No se puede eliminar el autor porque tiene libros asociados.");
        }
    }

    // --- CATEGORIAS ---
    public function getAllCategories(): array
    {
        return $this->catRepo->findAll();
    }

    public function getCategory(int $id): ?array
    {
        return $this->catRepo->findById($id);
    }

    public function createCategory(array $data, int $userId): int
    {
        if ($this->catRepo->existsByName($data['nombre'])) {
            throw new Exception("Ya existe una categoría con el nombre '{$data['nombre']}'.");
        }

        $id = $this->catRepo->create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'estado' => $data['estado'] ?? 'activa'
        ]);

        $this->logger->logAction($userId, 'CREAR_CATEGORIA', "Categoría creada ID: $id", $id);
        return $id;
    }

    public function updateCategory(int $id, array $data, int $userId): void
    {
        if ($this->catRepo->existsByName($data['nombre'], $id)) {
            throw new Exception("Ya existe otra categoría con el nombre '{$data['nombre']}'.");
        }

        $this->catRepo->update($id, [
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'estado' => $data['estado'] ?? 'activa'
        ]);

        $this->logger->logAction($userId, 'ACTUALIZAR_CATEGORIA', "Categoría actualizada ID: $id", $id);
    }

    public function deleteCategory(int $id, int $userId): void
    {
        try {
            $this->catRepo->delete($id);
            $this->logger->logAction($userId, 'ELIMINAR_CATEGORIA', "Categoría eliminada ID: $id", $id);
        } catch (Exception $e) {
            throw new Exception("No se puede eliminar la categoría porque tiene libros asociados.");
        }
    }

    // --- EDITORIALES ---
    public function getAllEditorials(): array
    {
        return $this->editRepo->findAll();
    }

    public function getEditorial(int $id): ?array
    {
        return $this->editRepo->findById($id);
    }

    public function createEditorial(array $data, int $userId): int
    {
        if ($this->editRepo->existsByName($data['nombre'])) {
            throw new Exception("Ya existe una editorial con el nombre '{$data['nombre']}'.");
        }

        $id = $this->editRepo->create([
            'nombre' => $data['nombre'],
            'pais' => $data['pais'] ?? null,
            'telefono' => $data['contacto_telefono'] ?? null,
            'email' => $data['contacto_email'] ?? null,
            'sitio_web' => $data['sitio_web'] ?? null,
            'estado' => $data['estado'] ?? 'activa'
        ]);

        $this->logger->logAction($userId, 'CREAR_EDITORIAL', "Editorial creada ID: $id", $id);
        return $id;
    }

    public function updateEditorial(int $id, array $data, int $userId): void
    {
        if ($this->editRepo->existsByName($data['nombre'], $id)) {
            throw new Exception("Ya existe otra editorial con el nombre '{$data['nombre']}'.");
        }

        $this->editRepo->update($id, [
            'nombre' => $data['nombre'],
            'pais' => $data['pais'] ?? null,
            'telefono' => $data['contacto_telefono'] ?? null,
            'email' => $data['contacto_email'] ?? null,
            'sitio_web' => $data['sitio_web'] ?? null,
            'estado' => $data['estado'] ?? 'activa'
        ]);

        $this->logger->logAction($userId, 'ACTUALIZAR_EDITORIAL', "Editorial actualizada ID: $id", $id);
    }

    public function deleteEditorial(int $id, int $userId): void
    {
        try {
            $this->editRepo->delete($id);
            $this->logger->logAction($userId, 'ELIMINAR_EDITORIAL', "Editorial eliminada ID: $id", $id);
        } catch (Exception $e) {
            throw new Exception("No se puede eliminar la editorial porque tiene libros asociados.");
        }
    }
}
