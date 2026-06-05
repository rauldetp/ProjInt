<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class AdminEntrepriseController extends Controller
{
    public function index()
    {
        $entreprises = Entreprise::withCount(['coordinateurs', 'collectes'])
            ->whereNull('parent_id')
            ->orderBy('nom')
            ->get();

        return response()->json($entreprises);
    }

    public function show(Entreprise $entreprise)
    {
        return response()->json($entreprise);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'              => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:entreprises,slug|regex:/^[a-z0-9\-]+$/',
            'nb_employes'      => 'nullable|integer|min:1',
            'domaine'          => 'nullable|string|max:255',
            'adresse'          => 'nullable|string|max:255',
            'ville'            => 'nullable|string|max:255',
            'npa'              => 'nullable|string|max:10',
            'logo'             => 'nullable|string|max:1000',
            'couleur_primaire' => 'nullable|string|max:7',
            'parent_id'        => 'nullable|exists:entreprises,id',
        ]);

        // Génération / unicité du slug
        $base = Str::slug($validated['slug'] ?? $validated['nom']);
        $slug = $base;
        $i = 1;
        while (Entreprise::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        $validated['slug'] = $slug;

        $entreprise = Entreprise::create($validated);

        return response()->json($entreprise, Response::HTTP_CREATED);
    }

    public function update(Request $request, Entreprise $entreprise)
    {
        $validated = $request->validate([
            'nom'              => 'required|string|max:255',
            'slug'             => 'required|string|max:255|regex:/^[a-z0-9\-]+$/|unique:entreprises,slug,' . $entreprise->id,
            'nb_employes'      => 'nullable|integer|min:1',
            'domaine'          => 'nullable|string|max:255',
            'adresse'          => 'nullable|string|max:255',
            'ville'            => 'nullable|string|max:255',
            'npa'              => 'nullable|string|max:10',
            'logo'             => 'nullable|string|max:1000',
            'couleur_primaire' => 'nullable|string|max:7',
            'parent_id'        => 'nullable|exists:entreprises,id',
        ]);

        $entreprise->update($validated);

        return response()->json($entreprise);
    }

    public function destroy(Entreprise $entreprise)
    {
        $entreprise->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
