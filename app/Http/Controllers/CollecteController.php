<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use Illuminate\Http\Response;

class CollecteController extends Controller
{
    public function incrementInscrits(Collecte $collecte)
    {
        $collecte->increment('nb_inscrits_estime');

        return response()->json([
            'nb_inscrits_estime' => $collecte->nb_inscrits_estime,
        ], Response::HTTP_OK);
    }
}
