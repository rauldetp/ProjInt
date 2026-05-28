<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trophee;
use App\Models\Entreprise;
use App\Models\Admin;

class TropheeSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();

        $trophees = [
            [
                'entreprise_slug' => 'pictet',
                'annee' => 2024,
                'commentaire' => 'Meilleur taux de participation avec 94% de dons réalisés sur les inscrits. 23 primo-donneurs recrutés.',
            ],
            [
                'entreprise_slug' => 'rolex',
                'annee' => 2023,
                'commentaire' => 'Engagement exceptionnel avec 4 collectes organisées dans l\'année et un taux de fidélisation record.',
            ],
            [
                'entreprise_slug' => 'bcge',
                'annee' => 2022,
                'commentaire' => 'Première entreprise à dépasser l\'objectif de 50 dons en une seule collecte.',
            ],
        ];

        foreach ($trophees as $data) {
            $entreprise = Entreprise::where('slug', $data['entreprise_slug'])->first();

            Trophee::create([
                'entreprise_id' => $entreprise->id,
                'admin_id' => $admin->id,
                'annee' => $data['annee'],
                'commentaire' => $data['commentaire'],
            ]);
        }
    }
}
