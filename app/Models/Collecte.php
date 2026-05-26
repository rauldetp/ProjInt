<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collecte extends Model
{
    protected $fillable = [
        'entreprise_id',
        'date_debut',
        'date_fin',
        'sur_site',
        'lien_rdv_externe',
        'active',
        'nb_inscrits_estime',
        'nb_dons_realises',
        'nb_nouveaux_donneurs',
        'validated_by',
        'validated_at',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'validated_at' => 'datetime',
        'sur_site' => 'boolean',
        'active' => 'boolean',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function estActive()
    {
        return $this->active === true;
    }

    public function estValidee()
    {
        return $this->validated_by !== null;
    }
}
