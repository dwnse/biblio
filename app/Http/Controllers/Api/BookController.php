<?php

namespace App\Http\Controllers\Api;

use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController
{
    public function index(Request $request)
    {
        $query = Libro::with(['autores', 'categorias', 'editorial']);

        // Filtros
        if ($request->has('q') && $request->q) {
            $query->where('titulo', 'like', '%' . $request->q . '%')
                  ->orWhere('isbn', 'like', '%' . $request->q . '%');
        }

        if ($request->has('categoria') && $request->categoria) {
            $query->whereHas('categorias', function($q) use ($request) {
                $q->where('id_categoria', $request->categoria);
            });
        }

        $books = $query->paginate(12);

        return response()->json($books);
    }

    public function show($id)
    {
        $book = Libro::with(['autores', 'categorias', 'editorial', 'resenas.usuario'])->findOrFail($id);

        // Calcular rating promedio
        $averageRating = $book->resenas->where('estado', 'visible')->avg('calificacion');

        $book->average_rating = $averageRating;

        return response()->json($book);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:200',
            'isbn' => 'nullable|string|max:20|unique:libros',
            'descripcion' => 'nullable|string',
            'anio_publicacion' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'id_editorial' => 'nullable|exists:editorial,id_editorial',
            'autores' => 'array',
            'autores.*' => 'exists:autores,id_autor',
            'categorias' => 'array',
            'categorias.*' => 'exists:categorias,id_categoria',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $book = Libro::create($request->only([
            'titulo', 'isbn', 'descripcion', 'anio_publicacion', 'id_editorial'
        ]));

        if ($request->has('autores')) {
            $book->autores()->attach($request->autores);
        }

        if ($request->has('categorias')) {
            $book->categorias()->attach($request->categorias);
        }

        return response()->json($book->load(['autores', 'categorias', 'editorial']), 201);
    }

    public function update(Request $request, $id)
    {
        $book = Libro::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:200',
            'isbn' => 'nullable|string|max:20|unique:libros,isbn,' . $id . ',id_libro',
            'descripcion' => 'nullable|string',
            'anio_publicacion' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'id_editorial' => 'nullable|exists:editorial,id_editorial',
            'autores' => 'array',
            'autores.*' => 'exists:autores,id_autor',
            'categorias' => 'array',
            'categorias.*' => 'exists:categorias,id_categoria',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $book->update($request->only([
            'titulo', 'isbn', 'descripcion', 'anio_publicacion', 'id_editorial'
        ]));

        if ($request->has('autores')) {
            $book->autores()->sync($request->autores);
        }

        if ($request->has('categorias')) {
            $book->categorias()->sync($request->categorias);
        }

        return response()->json($book->load(['autores', 'categorias', 'editorial']));
    }

    public function destroy($id)
    {
        $book = Libro::findOrFail($id);
        $book->delete();

        return response()->json(['message' => 'Libro eliminado']);
    }
}
