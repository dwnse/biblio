<?php

namespace App\Http\Controllers\Api;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController
{
    public function index()
    {
        $usuarios = Usuario::with('rol')->get();
        return response()->json($usuarios);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apellidoP' => 'nullable|string|max:100',
            'apellidoM' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:150|unique:usuarios',
            'contrasena' => 'required|string|min:6',
            'telefono' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $usuario = Usuario::create([
            'id_rol' => 2, // Usuario regular
            'nombre' => $request->nombre,
            'apellidoP' => $request->apellidoP,
            'apellidoM' => $request->apellidoM,
            'email' => $request->email,
            'contrasena' => Hash::make($request->contrasena),
            'telefono' => $request->telefono,
            'estado' => 'activo',
        ]);

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'usuario' => $usuario,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'contrasena' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->contrasena, $usuario->contrasena)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        if ($usuario->estado !== 'activo') {
            return response()->json(['message' => 'Cuenta inactiva'], 403);
        }

        $usuario->update(['ultimo_acceso' => now()]);
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'usuario' => $usuario,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout exitoso']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
