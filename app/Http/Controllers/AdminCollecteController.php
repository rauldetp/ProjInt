<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminCollecteController extends Controller
{
    public function index()
    {
        $collectes = Collecte::with(['entreprise', 'coordinateur.user'])
            ->orderBy('date_debut', 'desc')
            ->get();

        return response()->json($collectes);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'entreprise_id'      => 'required|exists:entreprises,id',
            'coordinateur_id'    => 'nullable|exists:coordinateurs,id',
            'date_debut'         => 'required|date',
            'date_fin'           => 'nullable|date|after_or_equal:date_debut',
            'sur_site'           => 'required|boolean',
            'lieu'               => 'nullable|string|max:255',
            'horaires'           => 'nullable|string|max:255',
            'objectif_dons'      => 'nullable|integer|min:1',
            'lien_rdv_externe'   => 'nullable|url',
            'active'             => 'boolean',
            'statut'             => 'in:en_attente,validee,terminee',
        ]);

        $data['admin_id'] = $request->user()->admin->id;
        $data['statut'] = $data['statut'] ?? 'en_attente';
        $data['active'] = $data['active'] ?? false;

        $collecte = Collecte::create($data);

        return response()->json($collecte->load('entreprise'), Response::HTTP_CREATED);
    }

    public function update(Request $request, Collecte $collecte)
    {
        $data = $request->validate([
            'entreprise_id'      => 'sometimes|exists:entreprises,id',
            'coordinateur_id'    => 'nullable|exists:coordinateurs,id',
            'date_debut'         => 'sometimes|date',
            'date_fin'           => 'nullable|date|after_or_equal:date_debut',
            'sur_site'           => 'sometimes|boolean',
            'lieu'               => 'nullable|string|max:255',
            'horaires'           => 'nullable|string|max:255',
            'objectif_dons'      => 'nullable|integer|min:1',
            'lien_rdv_externe'   => 'nullable|url',
            'active'             => 'sometimes|boolean',
            'statut'             => 'sometimes|in:en_attente,validee,terminee',
        ]);

        $collecte->update($data);

        return response()->json($collecte->load('entreprise'));
    }

    public function destroy(Collecte $collecte)
    {
        $collecte->delete();
        return response()->json(['message' => 'Collecte supprimée.']);
    }

    public function updateStatut(Request $request, Collecte $collecte)
    {
        $data = $request->validate([
            'statut' => 'required|in:en_attente,validee,terminee',
        ]);

        $collecte->update([
            'statut' => $data['statut'],
            'active' => $data['statut'] === 'validee',
        ]);

        return response()->json($collecte);
    }
}
