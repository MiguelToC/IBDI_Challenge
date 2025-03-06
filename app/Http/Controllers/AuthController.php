<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'role' => 'required|string|in:admin,user',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5|confirmed'
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'role' => $request->role,
            'email' => $request->email,
            'password' => bcrypt($request->get('password')),
        ]);

        return response()->json(['message' => 'Usuario creado correctamente :D'], 201);
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Credenciales incorrectas'], 400);
            }
            return response()->json(['token' => $token], 200);
        } catch (JWTException) {
            return response()->json(['message' => 'No se pudo crear el token'], 500);
        }
    }

    public function getUser()
    {
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }
}
