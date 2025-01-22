<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->count(10)->create([
            'role' => 'organizer',
        ]);

        User::factory()->count(50)->create([
            'role' => 'participant',
        ]);

        User::factory()->create([
            'name' => 'Organizador Teste',
            'email' => 'organizador@teste.com',
            'password' => bcrypt('12345678'),
            'role' => 'organizer',
        ]);

        
        User::factory()->create([
            'name' => 'Participante Teste',
            'email' => 'participante@teste.com',
            'password' => bcrypt('12345678'),
            'role' => 'participant',
        ]);
    }
}
