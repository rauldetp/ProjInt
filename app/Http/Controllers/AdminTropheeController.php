<?php

namespace App\Http\Controllers;

use App\Models\Trophee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminTropheeController extends Controller
{
    public function index()
    {
        return response()->json(
            Trophee::with('entreprise')->orderBy('annee', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'annee'         => 'required|integer|min:2000|max:2100',
            'commentaire'   => 'nullable|string|max:500',
        ]);

        $data['admin_id'] = $request->user()->admin->id;

        $trophee = Trophee::create($data);

        return response()->json($trophee->load('entreprise'), Response::HTTP_CREATED);
    }

    public function destroy(Trophee $trophee)
    {
        $trophee->delete();
        return response()->json(['message' => 'Trophée supprimé.']);
    }

    public function palmares()
    {
        $trophees = Trophee::with('entreprise')
            ->orderBy('annee', 'desc')
            ->get()
            ->groupBy('annee')
            ->map(fn ($items, $annee) => [
                'annee'    => (int) $annee,
                'laureats' => $items->map(fn ($t) => [
                    'entreprise'  => $t->entreprise?->nom,
                    'commentaire' => $t->commentaire,
                ])->values(),
            ])
            ->values();

        return response()->json($trophees);
    }
}
