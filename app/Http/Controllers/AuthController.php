<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password'])
        ]);

        $token = $user->createToken('user_auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function login(Request $request){

        if(!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login data'
            ], 401);
        };

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('user_auth_token')->plainTextToken;

        return response()->json([
            'acces_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function infoUser(Request $request) {

        return response()->json([
            'request-user' => $request->user(),
            'request-header' => $request->header('Authorization'),
            'request' => $request->all(),
            'request-only' => $request->only('email', 'password')
        ]);
    }
}
