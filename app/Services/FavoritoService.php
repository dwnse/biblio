<?php
namespace App\Services;

use App\Repositories\FavoritoRepository;

class FavoritoService
{
    private FavoritoRepository $favoritoRepository;

    public function __construct()
    {
        $this->favoritoRepository = new FavoritoRepository();
    }

    public function addFavorito(int $idUsuario, int $idLibro): bool
    {
        return $this->favoritoRepository->addFavorito($idUsuario, $idLibro);
    }

    public function removeFavorito(int $idUsuario, int $idLibro): bool
    {
        return $this->favoritoRepository->removeFavorito($idUsuario, $idLibro);
    }

    public function isFavorito(int $idUsuario, int $idLibro): bool
    {
        return $this->favoritoRepository->isFavorito($idUsuario, $idLibro);
    }

    public function getFavoritosByUsuario(int $idUsuario): array
    {
        return $this->favoritoRepository->getFavoritosByUsuario($idUsuario);
    }
}
