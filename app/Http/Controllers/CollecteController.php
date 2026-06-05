<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use App\Models\QuizResult;
use Illuminate\Http\Request;

class CollecteController extends Controller
{
    public function show(Collecte $collecte)
    {
        return response()->json($collecte->load('entreprise'));
    }

    public function incrementInscrits(Collecte $collecte)
    {
        $collecte->increment('nb_inscrits_estime');
        return response()->json(['nb_inscrits_estime' => $collecte->nb_inscrits_estime]);
    }

    public function storeQuizResult(Request $request, Collecte $collecte)
    {
        $data = $request->validate([
            'resultat' => 'required|in:eligible,non-eligible,incertain',
        ]);

        $eligible = match($data['resultat']) {
            'eligible'     => true,
            'non-eligible' => false,
            'incertain'    => null,
        };

        $quizResult = QuizResult::create([
            'collecte_id' => $collecte->id,
            'eligible'    => $eligible,
        ]);

        return response()->json($quizResult, 201);
    }
}
