<?php

namespace App\Http\Controllers\Api;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController
{
    public function index()
    {
        $categories = Categoria::where('estado', 'activa')->get();
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = Categoria::findOrFail($id);
        return response()->json($category);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:categorias',
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Categoria::create($request->all());
        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = Categoria::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:categorias,nombre,' . $id . ',id_categoria',
            'descripcion' => 'nullable|string',
            'estado' => 'in:activa,inactiva',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update($request->all());
        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = Categoria::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Categoría eliminada']);
    }
}
