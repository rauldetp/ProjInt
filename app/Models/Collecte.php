<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collecte extends Model
{
    protected $fillable = [
        'entreprise_id',
        'admin_id',
        'coordinateur_id',
        'date_debut',
        'date_fin',
        'sur_site',
        'lieu',
        'horaires',
        'objectif_dons',
        'lien_rdv_externe',
        'active',
        'statut',
        'nb_inscrits_estime',
        'nb_dons_realises',
        'nb_nouveaux_donneurs',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'sur_site' => 'boolean',
        'active' => 'boolean',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function coordinateur()
    {
        return $this->belongsTo(Coordinateur::class);
    }

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }

    public function estActive()
    {
        return $this->active === true;
    }

    public function estValidee()
    {
        return $this->statut === 'validee';
    }
}
