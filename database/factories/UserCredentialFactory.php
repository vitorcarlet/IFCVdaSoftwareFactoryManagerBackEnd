<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserCredential;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCredentialFactory extends Factory
{
    /**
     * O nome do modelo associado à factory.
     *
     * @var string
     */
    protected $model = UserCredential::class;

    /**
     * Definir o estado padrão para os atributos do modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Cria um novo usuário ou utiliza um existente
            'login' => $this->faker->unique()->userName, // Login fictício único
            'password' => bcrypt('password'), // Senha criptografada (padrão "password")
        ];
    }
}