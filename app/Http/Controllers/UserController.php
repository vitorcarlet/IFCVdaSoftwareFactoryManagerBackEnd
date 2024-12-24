<?php

namespace App\Http\Controllers;

use App\Models\UserCredential;
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
        $credentials = UserCredential::with('user')->get();
        return response()->json($credentials);
    }

    /**
     * Store a newly created user credential in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'login' => 'required|unique:user_credentials,login',
            'password' => 'required|min:8',
        ]);

        $credential = UserCredential::create([
            'user_id' => $request->user_id,
            'login' => $request->login,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($credential, 201);
    }

    /**
     * Display the specified user credential.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $credential = UserCredential::with('user')->findOrFail($id);
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

        $credential = UserCredential::findOrFail($id);
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
        $credential = UserCredential::findOrFail($id);
        $credential->delete();

        return response()->json(['message' => 'User credential deleted successfully.']);
    }
}
