<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Identifiants invalides.'], 401);
        }

        $token = $request->user()->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'role'  => $request->user()->role,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnecté.']);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('coordinateur.entreprise');

        return response()->json([
            'user' => $user,
            'role' => $user->role,
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:8',
            'entreprise'       => 'required|string|max:255',
            'telephone'        => 'nullable|string|max:20',
            'adresse'          => 'nullable|string|max:255',
            'ville'            => 'nullable|string|max:100',
            'npa'              => 'nullable|string|max:10',
            'domaine'          => 'nullable|in:horlogerie,banque,assurance,sante,industrie,technologie,autre',
            'nb_employes'      => 'nullable|integer|min:1',
            'poste'            => 'nullable|string|max:100',
            'couleur_primaire' => 'nullable|string|max:7',
            'logo'             => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
        ]);

        $logoUrl = null;
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $logoUrl = Storage::url($path);
        }

        $user = \App\Models\User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
            'role'     => 'coordinateur',
        ]);

        $slug = \Illuminate\Support\Str::slug($data['entreprise']);
        $base = $slug;
        $i = 1;
        while (\App\Models\Entreprise::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        $entreprise = \App\Models\Entreprise::create([
            'nom'              => $data['entreprise'],
            'slug'             => $slug,
            'nb_employes'      => $data['nb_employes'] ?? null,
            'domaine'          => $data['domaine'] ?? null,
            'adresse'          => $data['adresse'] ?? null,
            'ville'            => $data['ville'] ?? null,
            'npa'              => $data['npa'] ?? null,
            'couleur_primaire' => $data['couleur_primaire'] ?? null,
            'logo'             => $logoUrl,
        ]);

        \App\Models\Coordinateur::create([
            'user_id'       => $user->id,
            'entreprise_id' => $entreprise->id,
            'telephone'     => $data['telephone'] ?? null,
            'poste'         => $data['poste'] ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'role' => 'coordinateur', 'slug' => $slug], 201);
    }
}
