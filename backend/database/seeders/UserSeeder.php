<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;

class UserSeeder extends Seeder
{
    public function run()
    {
        $u1 = User::firstOrCreate([
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'cpf' => '12345678901',
            'profile_id' => 1
        ]);

        $u2 = User::firstOrCreate([
            'name' => 'Maria Souza',
            'email' => 'maria@example.com',
            'cpf' => '98765432100',
            'profile_id' => 2
        ]);

        // Criar endereços e vincular
        $a1 = Address::firstOrCreate([
            'street'       => 'Rua das Flores',
            'number'       => '100',
            'complement'   => 'Apto 12',
            'neighborhood' => 'Centro',
            'city'         => 'São Paulo',
            'state'        => 'SP',
            'zip'          => '01000000',
            'country'      => 'Brasil'
        ]);

        $a2 = Address::firstOrCreate([
            'street'       => 'Avenida Paulista',
            'number'       => '2000',
            'complement'   => '10º andar',
            'neighborhood' => 'Bela Vista',
            'city'         => 'São Paulo',
            'state'        => 'SP',
            'zip'          => '01310300',
            'country'      => 'Brasil'
        ]);

        $u1->addresses()->attach([$a1->id, $a2->id]);
        $u2->addresses()->attach([$a2->id]);
    }
}