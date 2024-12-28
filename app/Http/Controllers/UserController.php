<?php

namespace App\Http\Controllers;

use App\Models\UserCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of user credentials.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $credentials = UserCredentials::with('user')->get();
        return response()->json($credentials);
    }

    /**
     * Store a newly created user credential in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     //{
//     "name": "John Doe",
//     "gender": "male",
//     "email": "johndoe@example.com",
//     "login": "johndoe123",
//     "password": "password123"
// }

public function store(Request $request)
{
    // Validação personalizada para verificar se o e-mail ou login já existe
    $validator = \Validator::make($request->all(), [
        'name' => 'required|string|max:255', 
        'gender' => 'required|string|in:male,female,other',
        'email' => 'required|email|max:255',
        'birth_date' => 'required|date',
        'login' => 'required|max:255',
        'password' => 'required|min:8',
    ]);

    // Verificar se o e-mail já existe
    if (\App\Models\User::where('email', $request->email)->exists()) {
        return response()->json([
            'message' => 'The email address is already registered.'
        ], 409); // Código de conflito
    }

    // Verificar se o login já existe
    if (\App\Models\UserCredentials::where('login', $request->login)->exists()) {
        return response()->json([
            'message' => 'The login is already in use.'
        ], 409); // Código de conflito
    }

    // Criar o usuário
    $user = \App\Models\User::create([
        'name' => $request->name,
        'gender' => $request->gender,
        'email' => $request->email,
        'birth_date' => $request->birth_date,
    ]);

    // Criar as credenciais
    $credential = UserCredentials::create([
        'user_id' => $user->id,
        'login' => $request->login,
        'password' => Hash::make($request->password),
    ]);

    // Retornar resposta de sucesso
    return response()->json([
        'user' => $user,
        'credentials' => $credential,
    ], 201);
}

    /**
     * Display the specified user credential.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $credential = UserCredentials::with('user')->findOrFail($id);
        return response()->json($credential);
    }

    /**
     * Update the specified user credential in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'login' => 'sometimes|unique:user_credentials,login,' . $id,
            'password' => 'sometimes|min:8',
        ]);

        $credential = UserCredentials::findOrFail($id);
        $credential->update([
            'login' => $request->login ?? $credential->login,
            'password' => $request->password ? Hash::make($request->password) : $credential->password,
        ]);

        return response()->json($credential);
    }

    /**
     * Remove the specified user credential from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $credential = UserCredentials::findOrFail($id);
        $credential->delete();

        return response()->json(['message' => 'User credential deleted successfully.']);
    }
}
