<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Response;

class EntrepriseController extends Controller
{
    public function show(string $slug)
    {
        $entreprise = Entreprise::where('slug', $slug)->firstOrFail();

        $collecte = $entreprise->collectes()
            ->where('active', true)
            ->where('statut', 'validee')
            ->orderBy('date_debut', 'asc')
            ->first();

        return response()->json([
            'entreprise' => $entreprise,
            'collecte' => $collecte,
        ], Response::HTTP_OK);
    }
}
