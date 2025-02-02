<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserCredentials;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * O nome do modelo associado à factory.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Definir o estado padrão para os atributos do modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name, // Nome fictício
            'cpf' => $this->faker->unique()->regexify('[0-9]{11}'), // CPF fictício (11 dígitos únicos)
            'birth_date' => $this->faker->date('Y-m-d', '2005-01-01'), // Data de nascimento (até 2005 para 18+)
            'gender' => $this->faker->randomElement(['male', 'female', 'other']), // Gênero aleatório
            'email' => $this->faker->unique()->safeEmail(), // Gera um email único
            'is_active' => $this->faker->boolean(80), // 80% de chance de ser true
            'registration_number' => $this->faker->unique()->regexify('[A-Z0-9]{10}'), // Número de registro único
        ];
    }

    /**
     * Configura a factory para criar um usuário com credenciais associadas.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withCredentials()
    {
        return $this->afterCreating(function (User $user) {
            UserCredentials::create([
                'user_id' => $user->id,
                'login' => $this->faker->unique()->userName, // Login fictício único
                'password' => bcrypt('12345'), // Senha criptografada (padrão "password")
            ]);
        });
    }
}
