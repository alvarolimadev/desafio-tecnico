<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        Profile::firstOrCreate(
            ['name' => 'Administrador'],
            ['description' => 'Acesso total']
        );

        Profile::firstOrCreate(
            ['name' => 'Gerente'],
            ['description' => 'Acesso gerencial']
        );

        Profile::firstOrCreate(
            ['name' => 'Usuário'],
            ['description' => 'Acesso básico']
        );
    }
}