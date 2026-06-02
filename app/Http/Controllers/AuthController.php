<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8',
            'entreprise' => 'required|string|max:255',
            'telephone'  => 'nullable|string|max:20',
        ]);

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
            'nom'         => $data['entreprise'],
            'slug'        => $slug,
            'nb_employes' => null,
            'domaine'     => null,
            'adresse'     => null,
            'ville'       => null,
            'npa'         => null,
        ]);

        \App\Models\Coordinateur::create([
            'user_id'       => $user->id,
            'entreprise_id' => $entreprise->id,
            'telephone'     => $data['telephone'] ?? null,
            'poste'         => null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'role' => 'coordinateur'], 201);
    }
}
