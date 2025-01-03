<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller

{
    /**
     * Return a welcome message.
     */
    public function hello()
    {
        return response()->json(['message' => 'Welcome to the authentication service!']);
    }

    /**
     * Handle user registration request.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $user_credentialsRequest = $request->only('login', 'password');

        $user_credentials = UserCredentials::where('login', $user_credentialsRequest['login'])->first();

        if (!$user_credentials) {
            return response()->json(['error' => 'Invalid userCred'], 401);
        }
        Log::info('User credentials:', ['login' => $user_credentialsRequest['login'], 'user_credentials' => $user_credentials]);

        if (!$user_credentials || !Hash::check($request->password, $user_credentials->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = User::find($user_credentials->user_id);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }


    /**
     * Return the authenticated user's data.
     */
    public function me()
    {
        $user = Auth::user();

        return response()->json($user);
    }

    /**
  * Handle logout request.
  */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
