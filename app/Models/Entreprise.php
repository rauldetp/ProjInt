<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    protected $fillable = [
        'nom',
        'slug',
        'taille',
        'domaine',
        'adresse',
        'ville',
        'npa',
        'logo',
        'couleur_primaire',
    ];

    public function coordinateurs()
    {
        return $this->hasMany(Coordinateur::class);
    }

    public function collectes()
    {
        return $this->hasMany(Collecte::class);
    }

    public function label()
    {
        return $this->hasOne(Label::class);
    }

    public function trophees()
    {
        return $this->hasMany(Trophee::class);
    }
}
