<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function createUser(Request $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        $token = $user->createToken('api-token')->plainTextToken;
    
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais estão incorretas.'],
            ]);
        }
    
        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
        ]);
        
    }

    public function showUser(Request $request) {
        return $request->user();
    }

    public function logout(Request $request) {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Usuário já está deslogado'
            ], 401);
        }

        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Deslogado com sucesso'
        ]);
    }

}
