<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    public function show(Request $request, Collecte $collecte)
    {
        $coordinateur = $request->user()->coordinateur;

        if (!$coordinateur || $collecte->entreprise_id !== $coordinateur->entreprise_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        return response()->json($collecte);
    }

    public function update(Request $request, Collecte $collecte)
    {
        $coordinateur = $request->user()->coordinateur;

        if (!$coordinateur || $collecte->entreprise_id !== $coordinateur->entreprise_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $data = $request->validate([
            'titre'          => 'nullable|string|max:255',
            'date_debut'     => 'required|date',
            'date_fin'       => 'nullable|date|after_or_equal:date_debut',
            'sur_site'       => 'required|boolean',
            'lieu'           => 'nullable|string|max:255',
            'horaires'       => 'nullable|string|max:255',
            'objectif_dons'  => 'nullable|integer|min:1',
        ]);

        $collecte->update($data);

        return response()->json($collecte);
    }

    public function annuler(Request $request, Collecte $collecte)
    {
        $coordinateur = $request->user()->coordinateur;

        if (!$coordinateur || $collecte->entreprise_id !== $coordinateur->entreprise_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        if ($collecte->statut === 'terminee') {
            return response()->json(['error' => 'Impossible d\'annuler une collecte terminée.'], 422);
        }

        $collecte->update([
            'statut' => 'annulee',
            'active' => false,
        ]);

        return response()->json(['message' => 'Collecte annulée.']);
    }

    public function store(Request $request)
    {
        $coordinateur = $request->user()->coordinateur;

        if (!$coordinateur) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $data = $request->validate([
            'titre'          => 'nullable|string|max:255',
            'date_debut'     => 'required|date',
            'date_fin'       => 'nullable|date|after_or_equal:date_debut',
            'sur_site'       => 'required|boolean',
            'lieu'           => 'nullable|string|max:255',
            'horaires'       => 'nullable|string|max:255',
            'objectif_dons'  => 'nullable|integer|min:1',
        ]);

        $data['entreprise_id']  = $coordinateur->entreprise_id;
        $data['coordinateur_id'] = $coordinateur->id;
        $data['statut']         = 'en_attente';
        $data['active']         = false;
        $data['nb_inscrits_estime'] = 0;

        $collecte = Collecte::create($data);

        return response()->json($collecte->load('entreprise'), Response::HTTP_CREATED);
    }
}
