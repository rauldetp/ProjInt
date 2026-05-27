<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collecte;
use App\Models\Entreprise;
use App\Models\Admin;

class CollecteSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();

        $collectes = [
            [
                'entreprise_slug' => 'pictet',
                'date_debut' => '2026-06-15',
                'date_fin' => '2026-06-15',
                'sur_site' => true,
                'lieu' => 'Salle de conférence A',
                'horaires' => '09:00 - 17:00',
                'lien_rdv_externe' => 'https://rdv.cts-hug.ch/pictet',
                'active' => true,
                'statut' => 'validee',
                'nb_inscrits_estime' => 47,
                'nb_dons_realises' => 0,
                'nb_nouveaux_donneurs' => 0,
                'objectif_dons' => 50,
            ],
            [
                'entreprise_slug' => 'rolex',
                'date_debut' => '2026-06-20',
                'date_fin' => '2026-06-20',
                'sur_site' => true,
                'lieu' => 'Hall principal',
                'horaires' => '08:00 - 16:00',
                'lien_rdv_externe' => 'https://rdv.cts-hug.ch/rolex',
                'active' => true,
                'statut' => 'validee',
                'nb_inscrits_estime' => 38,
                'nb_dons_realises' => 0,
                'nb_nouveaux_donneurs' => 0,
                'objectif_dons' => 40,
            ],
            [
                'entreprise_slug' => 'ubs',
                'date_debut' => '2026-07-01',
                'date_fin' => '2026-07-01',
                'sur_site' => true,
                'lieu' => 'Salle polyvalente',
                'horaires' => '10:00 - 18:00',
                'lien_rdv_externe' => 'https://rdv.cts-hug.ch/ubs',
                'active' => false,
                'statut' => 'en_attente',
                'nb_inscrits_estime' => 0,
                'nb_dons_realises' => 0,
                'nb_nouveaux_donneurs' => 0,
                'objectif_dons' => 30,
            ],
            [
                'entreprise_slug' => 'bcge',
                'date_debut' => '2025-11-10',
                'date_fin' => '2025-11-10',
                'sur_site' => true,
                'lieu' => 'Salle de réunion',
                'horaires' => '09:00 - 15:00',
                'lien_rdv_externe' => 'https://rdv.cts-hug.ch/bcge',
                'active' => false,
                'statut' => 'validee',
                'nb_inscrits_estime' => 25,
                'nb_dons_realises' => 20,
                'nb_nouveaux_donneurs' => 5,
                'objectif_dons' => 25,
            ],
            [
                'entreprise_slug' => 'logitech',
                'date_debut' => '2025-10-05',
                'date_fin' => '2025-10-05',
                'sur_site' => true,
                'lieu' => 'Cafétéria',
                'horaires' => '08:30 - 16:30',
                'lien_rdv_externe' => 'https://rdv.cts-hug.ch/logitech',
                'active' => false,
                'statut' => 'validee',
                'nb_inscrits_estime' => 31,
                'nb_dons_realises' => 28,
                'nb_nouveaux_donneurs' => 8,
                'objectif_dons' => 30,
            ],
        ];

        foreach ($collectes as $data) {
            $entreprise = Entreprise::where('slug', $data['entreprise_slug'])->first();

            Collecte::create([
                'entreprise_id' => $entreprise->id,
                'admin_id' => $admin->id,
                'coordinateur_id' => null,
                'date_debut' => $data['date_debut'],
                'date_fin' => $data['date_fin'],
                'sur_site' => $data['sur_site'],
                'lieu' => $data['lieu'],
                'horaires' => $data['horaires'],
                'lien_rdv_externe' => $data['lien_rdv_externe'],
                'active' => $data['active'],
                'statut' => $data['statut'],
                'nb_inscrits_estime' => $data['nb_inscrits_estime'],
                'nb_dons_realises' => $data['nb_dons_realises'],
                'nb_nouveaux_donneurs' => $data['nb_nouveaux_donneurs'],
                'objectif_dons' => $data['objectif_dons'],
            ]);
        }
    }
}
