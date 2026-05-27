<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Label;
use App\Models\Entreprise;

class LabelSeeder extends Seeder
{
    public function run(): void
    {
        $labels = [
            [
                'entreprise_slug' => 'pictet',
                'date_attribution' => '2024-01-15',
                'date_expiration' => '2027-01-15',
                'actif' => true,
            ],
            [
                'entreprise_slug' => 'rolex',
                'date_attribution' => '2024-03-10',
                'date_expiration' => '2027-03-10',
                'actif' => true,
            ],
            [
                'entreprise_slug' => 'ubs',
                'date_attribution' => '2024-06-01',
                'date_expiration' => '2027-06-01',
                'actif' => true,
            ],
            [
                'entreprise_slug' => 'bcge',
                'date_attribution' => '2023-11-10',
                'date_expiration' => '2026-11-10',
                'actif' => true,
            ],
            [
                'entreprise_slug' => 'logitech',
                'date_attribution' => '2023-10-05',
                'date_expiration' => '2026-10-05',
                'actif' => true,
            ],
            [
                'entreprise_slug' => 'lombard-odier',
                'date_attribution' => '2022-05-20',
                'date_expiration' => '2025-05-20',
                'actif' => false,
            ],
            [
                'entreprise_slug' => 'patek-philippe',
                'date_attribution' => '2024-09-01',
                'date_expiration' => '2027-09-01',
                'actif' => true,
            ],
        ];

        foreach ($labels as $data) {
            $entreprise = Entreprise::where('slug', $data['entreprise_slug'])->first();

            Label::create([
                'entreprise_id' => $entreprise->id,
                'date_attribution' => $data['date_attribution'],
                'date_expiration' => $data['date_expiration'],
                'actif' => $data['actif'],
            ]);
        }
    }
}
