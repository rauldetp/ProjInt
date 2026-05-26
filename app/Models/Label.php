<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable = [
        'entreprise_id',
        'date_attribution',
        'date_expiration',
        'actif',
    ];

    protected $casts = [
        'date_attribution' => 'date',
        'date_expiration' => 'date',
        'actif' => 'boolean',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function estValide()
    {
        return $this->actif && $this->date_expiration->isFuture();
    }

    public function renouveler()
    {
        $this->date_expiration = now()->addYear();
        $this->save();
    }
}
