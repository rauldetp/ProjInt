<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\Coordinateur;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin CTS
        $admin = User::create([
            'name' => 'Eustache Dumont',
            'email' => 'admin@cts-hug.ch',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        Admin::create([
            'user_id' => $admin->id,
        ]);

        // Coordinateurs RH
        $coordinateurs = [
            [
                'name' => 'Sandra Martin',
                'email' => 'sandra.martin@pictet.com',
                'entreprise_slug' => 'pictet',
                'telephone' => '+41 22 000 00 01',
                'poste' => 'Responsable RH',
            ],
            [
                'name' => 'Marc Dubois',
                'email' => 'marc.dubois@rolex.com',
                'entreprise_slug' => 'rolex',
                'telephone' => '+41 22 000 00 02',
                'poste' => 'Coordinateur RSE',
            ],
            [
                'name' => 'Julie Favre',
                'email' => 'julie.favre@ubs.com',
                'entreprise_slug' => 'ubs',
                'telephone' => '+41 22 000 00 03',
                'poste' => 'Responsable RH',
            ],
        ];

        foreach ($coordinateurs as $coord) {
            $entreprise = \App\Models\Entreprise::where('slug', $coord['entreprise_slug'])->first();

            $user = User::create([
                'name' => $coord['name'],
                'email' => $coord['email'],
                'password' => bcrypt('password'),
                'role' => 'coordinateur',
            ]);

            Coordinateur::create([
                'user_id' => $user->id,
                'entreprise_id' => $entreprise->id,
                'telephone' => $coord['telephone'],
                'poste' => $coord['poste'],
            ]);
        }
    }
}
