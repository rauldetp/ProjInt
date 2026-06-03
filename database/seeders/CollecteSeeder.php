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
                'titre' => 'Collecte de printemps',
                'date_debut' => '2026-06-15',
                'date_fin' => '2026-06-15',
                'sur_site' => true,
                'lieu' => 'Salle de conférence A, Route de Chêne 58, 1211 Genève',
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
                'entreprise_slug' => 'pictet',
                'titre' => 'Campagne Saint-Valentin',
                'date_debut' => '2026-02-14',
                'date_fin' => '2026-02-14',
                'sur_site' => true,
                'lieu' => 'Salle polyvalente, Route de Chêne 58, 1211 Genève',
                'horaires' => '08:30 - 16:00',
                'lien_rdv_externe' => 'https://rdv.cts-hug.ch/pictet',
                'active' => false,
                'statut' => 'terminee',
                'nb_inscrits_estime' => 52,
                'nb_dons_realises' => 48,
                'nb_nouveaux_donneurs' => 12,
                'objectif_dons' => 50,
            ],
            [
                'entreprise_slug' => 'rolex',
                'titre' => 'Don du sang en famille',
                'date_debut' => '2026-06-20',
                'date_fin' => '2026-06-20',
                'sur_site' => true,
                'lieu' => 'Hall principal, Rue François-Dussaud 3-5-7, 1211 Genève',
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
                'titre' => 'Campagne d\'été',
                'date_debut' => '2026-07-01',
                'date_fin' => '2026-07-01',
                'sur_site' => true,
                'lieu' => 'Salle polyvalente, Rue de Cornavin 6, 1201 Genève',
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
                'titre' => 'Collecte annuelle BCGE',
                'date_debut' => '2025-11-10',
                'date_fin' => '2025-11-10',
                'sur_site' => true,
                'lieu' => 'Salle de réunion, Quai de l\'Île 17, 1211 Genève',
                'horaires' => '09:00 - 15:00',
                'lien_rdv_externe' => 'https://rdv.cts-hug.ch/bcge',
                'active' => false,
                'statut' => 'terminee',
                'nb_inscrits_estime' => 25,
                'nb_dons_realises' => 20,
                'nb_nouveaux_donneurs' => 5,
                'objectif_dons' => 25,
            ],
            [
                'entreprise_slug' => 'logitech',
                'titre' => 'Don solidaire Logitech',
                'date_debut' => '2025-10-05',
                'date_fin' => '2025-10-05',
                'sur_site' => true,
                'lieu' => 'Cafétéria, EPFL Innovation Park, 1015 Lausanne',
                'horaires' => '08:30 - 16:30',
                'lien_rdv_externe' => 'https://rdv.cts-hug.ch/logitech',
                'active' => false,
                'statut' => 'terminee',
                'nb_inscrits_estime' => 31,
                'nb_dons_realises' => 28,
                'nb_nouveaux_donneurs' => 8,
                'objectif_dons' => 30,
            ],
        ];

        foreach ($collectes as $data) {
            $entreprise = Entreprise::where('slug', $data['entreprise_slug'])->first();

            Collecte::create([
                'titre' => $data['titre'] ?? null,
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
