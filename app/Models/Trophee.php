<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trophee extends Model
{
    protected $fillable = [
        'entreprise_id',
        'admin_id',
        'annee',
        'commentaire',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
