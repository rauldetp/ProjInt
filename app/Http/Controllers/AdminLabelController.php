<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminLabelController extends Controller
{
    public function index()
    {
        return response()->json(
            Label::with('entreprise')->orderBy('created_at', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'entreprise_id'    => 'required|exists:entreprises,id',
            'date_attribution' => 'required|date',
            'date_expiration'  => 'required|date|after:date_attribution',
            'actif'            => 'boolean',
        ]);

        $data['actif'] = $data['actif'] ?? true;

        $label = Label::create($data);

        return response()->json($label->load('entreprise'), Response::HTTP_CREATED);
    }

    public function update(Request $request, Label $label)
    {
        $data = $request->validate([
            'date_attribution' => 'sometimes|date',
            'date_expiration'  => 'sometimes|date|after:date_attribution',
            'actif'            => 'sometimes|boolean',
        ]);

        $label->update($data);

        return response()->json($label->load('entreprise'));
    }

    public function destroy(Label $label)
    {
        $label->delete();
        return response()->json(['message' => 'Label supprimé.']);
    }
}
