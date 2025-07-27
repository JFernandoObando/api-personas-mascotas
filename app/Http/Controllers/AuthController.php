<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);
        $user->update(['jwt_token' => $token]);

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user' => [
                'name'  => $user->name,
                'email' => $user->email,
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }
    
        $user = JWTAuth::user();
        $user->update(['jwt_token' => $token]);
    
        return response()->json([
            'message' => 'Inicio de sesión correcto',
            'user' => [
                'name'  => $user->name,
                'email' => $user->email,
            ],
            'token' => $token
        ], 200);
        
    }
    

    public function me()
    {
        return response()->json(auth()->user());
    }
}
