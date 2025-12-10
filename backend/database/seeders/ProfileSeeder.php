<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    public function run()
    {
        Profile::create(['name' => 'Administrador', 'description' => 'Acesso total']);
        Profile::create(['name' => 'Gerente', 'description' => 'Gerencia usuários']);
        Profile::create(['name' => 'Usuário', 'description' => 'Acesso básico']);
    }
}