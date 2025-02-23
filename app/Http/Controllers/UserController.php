<?php

namespace App\Http\Controllers;

use App\Models\UserCredentials;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


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
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|max:255', 
        //     'gender' => 'required|string|in:male,female,other',
        //     'email' => 'required|email|max:255',
        //     'birth_date' => 'required|date',
        //     'login' => 'required|max:255',
        //     'password' => 'required|min:8',
        // ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.unique' => 'O e-mail já está em uso.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.confirmed' => 'As senhas não coincidem.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }



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

        try {
            DB::beginTransaction();

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
                'password' => $request->password,
            ]);

            $user->assignRole('student');

            DB::commit();

            // Retornar resposta de sucesso
            return response()->json([
                'user' => $user,
                'credentials' => $credential,
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'An error occurred while creating the user.',
                'error' => $e->getMessage(),
            ], 500);
        }

        // Criar o usuário

    }

    /**
     * Display the specified user credential.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
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

    /**
     * Change the password of the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePass(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8',
        ]);

        $userId = Auth::id();
        $credential = UserCredentials::where('user_id', $userId)->firstOrFail();
        $credential->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password changed successfully.']);
    }
}
