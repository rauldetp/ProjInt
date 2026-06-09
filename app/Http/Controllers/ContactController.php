<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'nom'        => ['nullable', 'string', 'max:255'],
            'prenom'     => ['required', 'string', 'max:255'],
            'entreprise' => ['nullable', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255'],
            'sujet'      => ['required', 'string', 'max:255'],
            'message'    => ['required', 'string', 'max:5000'],
        ]);

        // Adresse de destination — placeholder pour l'instant.
        $destinataire = 'contact@example.com';

        $corps = "Nouveau message de contact\n\n"
            . "Nom : " . ($data['nom'] ?? '') . "\n"
            . "Prénom : " . $data['prenom'] . "\n"
            . "Entreprise : " . ($data['entreprise'] ?? '') . "\n"
            . "Email : " . $data['email'] . "\n"
            . "Sujet : " . $data['sujet'] . "\n\n"
            . "Message :\n" . $data['message'];

        Mail::raw($corps, function ($mail) use ($destinataire, $data) {
            $mail->to($destinataire)
                ->subject('Contact CTS — ' . $data['sujet'])
                ->replyTo($data['email']);
        });

        return response()->json(['message' => 'Message envoyé']);
    }
}
