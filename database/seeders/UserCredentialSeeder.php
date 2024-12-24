<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder; 
use App\Models\User;
use App\Models\UserCredential;

class UserCredentialSeeder extends Seeder
{
    /**
     * Executar o seeder.
     * 
     * @return void
     */
    public function run()
    {
        //Criar 5 usuários com credenciais
        User::factory()->count(10)->create()->each(function ($user) {
            UserCredential::factory()->for($user)->create();
        });
    }
}