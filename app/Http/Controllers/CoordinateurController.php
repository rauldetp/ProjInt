<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoordinateurController extends Controller
{
    public function collectes(Request $request)
    {
        $coordinateur = $request->user()->coordinateur;

        if (!$coordinateur) {
            return response()->json([], 200);
        }

        $collectes = $coordinateur->entreprise
            ->collectes()
            ->orderBy('date_debut', 'desc')
            ->get();

        return response()->json([
            'entreprise' => $coordinateur->entreprise,
            'collectes'  => $collectes,
        ]);
    }
}
