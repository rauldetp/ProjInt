<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entreprise;

class EntrepriseSeeder extends Seeder
{
    public function run(): void
    {
        $entreprises = [
            [
                'nom' => 'UBS',
                'slug' => 'ubs',
                'nb_employes' => 45000,
                'domaine' => 'banque',
                'adresse' => 'Bahnhofstrasse 45',
                'ville' => 'Genève',
                'npa' => 1211,
                'logo' => null,
                'couleur_primaire' => '#E60028',
                'parent_id' => null,
            ],
            [
                'nom' => 'Rolex SA',
                'slug' => 'rolex',
                'nb_employes' => 8000,
                'domaine' => 'horlogerie',
                'adresse' => 'Rue François-Dussaud 3',
                'ville' => 'Genève',
                'npa' => 1211,
                'logo' => null,
                'couleur_primaire' => '#2D6A4F',
                'parent_id' => null,
            ],
            [
                'nom' => 'Banque Pictet',
                'slug' => 'pictet',
                'nb_employes' => 5000,
                'domaine' => 'banque',
                'adresse' => 'Route des Acacias 60',
                'ville' => 'Genève',
                'npa' => 1211,
                'logo' => null,
                'couleur_primaire' => '#1D3557',
                'parent_id' => null,
            ],
            [
                'nom' => 'Lombard Odier',
                'slug' => 'lombard-odier',
                'nb_employes' => 2500,
                'domaine' => 'banque',
                'adresse' => 'Rue de la Corraterie 11',
                'ville' => 'Genève',
                'npa' => 1204,
                'logo' => null,
                'couleur_primaire' => '#7B2D8B',
                'parent_id' => null,
            ],
            [
                'nom' => 'BCGE',
                'slug' => 'bcge',
                'nb_employes' => 1000,
                'domaine' => 'banque',
                'adresse' => 'Quai de l\'Île 17',
                'ville' => 'Genève',
                'npa' => 1204,
                'logo' => null,
                'couleur_primaire' => '#E8264A',
                'parent_id' => null,
            ],
            [
                'nom' => 'Logitech',
                'slug' => 'logitech',
                'nb_employes' => 3000,
                'domaine' => 'technologie',
                'adresse' => 'Chemin du Champ-des-Filles 6',
                'ville' => 'Genève',
                'npa' => 1228,
                'logo' => null,
                'couleur_primaire' => '#000000',
                'parent_id' => null,
            ],
            [
                'nom' => 'Patek Philippe',
                'slug' => 'patek-philippe',
                'nb_employes' => 2000,
                'domaine' => 'horlogerie',
                'adresse' => 'Chemin du Pont-du-Centenaire 141',
                'ville' => 'Genève',
                'npa' => 1228,
                'logo' => null,
                'couleur_primaire' => '#1A1A1A',
                'parent_id' => null,
            ],
            [
                'nom' => 'Geneva Trading',
                'slug' => 'geneva-trading',
                'nb_employes' => 500,
                'domaine' => 'autre',
                'adresse' => 'Rue du Rhône 14',
                'ville' => 'Genève',
                'npa' => 1204,
                'logo' => null,
                'couleur_primaire' => '#0C447C',
                'parent_id' => null,
            ],
            [
                'nom' => 'SIG — Services Industriels de Genève',
                'slug' => 'sig',
                'nb_employes' => 1800,
                'domaine' => 'industrie',
                'adresse' => 'Chemin du Château-Bloch 2',
                'ville' => 'Genève',
                'npa' => 1219,
                'logo' => null,
                'couleur_primaire' => '#27500A',
                'parent_id' => null,
            ],
            [
                'nom' => 'HUG — Hôpitaux Universitaires de Genève',
                'slug' => 'hug',
                'nb_employes' => 12000,
                'domaine' => 'sante',
                'adresse' => 'Rue Gabrielle-Perret-Gentil 4',
                'ville' => 'Genève',
                'npa' => 1211,
                'logo' => null,
                'couleur_primaire' => '#1D9E75',
                'parent_id' => null,
            ],
        ];

        foreach ($entreprises as $entreprise) {
            Entreprise::create($entreprise);
        }
    }
}
