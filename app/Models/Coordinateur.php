<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coordinateur extends Model
{
    protected $fillable = [
        'user_id',
        'entreprise_id',
        'telephone',
        'poste',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
}
