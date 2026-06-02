<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Response;

class EntrepriseController extends Controller
{
    public function show(string $slug)
    {
        $entreprise = Entreprise::where('slug', $slug)->with(['trophees' => fn($q) => $q->orderBy('annee', 'desc')])
            ->firstOrFail();

        $collecte = $entreprise->collectes()
            ->where('active', true)
            ->where('statut', 'validee')
            ->orderBy('date_debut', 'asc')
            ->first();

        $label = $entreprise->label()
            ->where('actif', true)
            ->where('date_expiration', '>=', now())
            ->first();

        return response()->json([
            'entreprise' => $entreprise,
            'collecte' => $collecte,
            'label' => $label,
            'trophees' => $entreprise->trophees ?? [],
        ], Response::HTTP_OK);
    }
}
