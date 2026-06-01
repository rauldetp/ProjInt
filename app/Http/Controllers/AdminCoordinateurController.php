<?php

namespace App\Http\Controllers;

use App\Models\Coordinateur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AdminCoordinateurController extends Controller
{
    public function index()
    {
        $coordinateurs = Coordinateur::with(['user', 'entreprise'])
            ->get();
        return response()->json($coordinateurs);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8',
            'entreprise_id' => 'required|exists:entreprises,id',
            'telephone'     => 'nullable|string|max:50',
            'poste'         => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'coordinateur',
        ]);

        $coordinateur = Coordinateur::create([
            'user_id'       => $user->id,
            'entreprise_id' => $data['entreprise_id'],
            'telephone'     => $data['telephone'] ?? null,
            'poste'         => $data['poste'] ?? null,
        ]);

        return response()->json($coordinateur->load(['user', 'entreprise']), Response::HTTP_CREATED);
    }

    public function update(Request $request, Coordinateur $coordinateur)
    {
        $data = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'email'         => 'sometimes|email|unique:users,email,' . $coordinateur->user_id,
            'entreprise_id' => 'sometimes|exists:entreprises,id',
            'telephone'     => 'nullable|string|max:50',
            'poste'         => 'nullable|string|max:255',
        ]);

        $coordinateur->user->update(array_filter([
            'name'  => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
        ]));

        $coordinateur->update(array_filter([
            'entreprise_id' => $data['entreprise_id'] ?? null,
            'telephone'     => $data['telephone'] ?? null,
            'poste'         => $data['poste'] ?? null,
        ]));

        return response()->json($coordinateur->load(['user', 'entreprise']));
    }

    public function destroy(Coordinateur $coordinateur)
    {
        $coordinateur->user->delete();
        return response()->json(['message' => 'Coordinateur supprimé.']);
    }
}
