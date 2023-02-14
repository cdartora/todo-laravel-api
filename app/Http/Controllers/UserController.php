<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class UserController extends Controller
{
    /**
     * Register a new user in the database.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'username' => 'required|string|min:5|max:20',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|max:15'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $password = Hash::make($request->password);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $password,
        ]);

        return response()->json(['message' => 'User created successfully']);
    }

    /**
     * Login a user and return a JWT Token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        return response()->json([
            'token' => $token,
            'type' => 'bearer',
        ]);
    }
}