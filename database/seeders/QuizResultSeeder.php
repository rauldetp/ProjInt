<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizResult;
use App\Models\Collecte;

class QuizResultSeeder extends Seeder
{
    public function run(): void
    {
        $collectes = Collecte::all();

        foreach ($collectes as $collecte) {
            // 10 résultats par collecte
            for ($i = 0; $i < 10; $i++) {
                QuizResult::create([
                    'collecte_id' => $collecte->id,
                    'eligible' => rand(0, 1) === 1,
                ]);
            }
        }
    }
}
