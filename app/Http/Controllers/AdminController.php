<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use App\Models\Entreprise;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    public function stats()
    {
        $collectesActives = Collecte::where('active', true)
            ->where('statut', 'validee')
            ->count();

        $totalInscrits = Collecte::where('active', true)
            ->where('statut', 'validee')
            ->sum('nb_inscrits_estime');

        $totalDons = Collecte::sum('nb_dons_realises');

        $entreprisesPartenaires = Entreprise::whereHas('collectes', function ($q) {
            $q->where('statut', 'validee');
        })->count();

        $collectesEnAttente = Collecte::where('statut', 'en_attente')->count();

        return response()->json([
            'collectes_actives'       => $collectesActives,
            'collectes_en_attente'    => $collectesEnAttente,
            'total_inscrits'          => $totalInscrits,
            'total_dons_realises'     => $totalDons,
            'entreprises_partenaires' => $entreprisesPartenaires,
        ]);
    }

    public function entreprises()
    {
        $entreprises = Entreprise::withCount('collectes')
            ->orderBy('nom')
            ->get();

        return response()->json($entreprises);
    }
}
