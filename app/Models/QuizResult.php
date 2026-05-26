<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    protected $fillable = [
        'collecte_id',
        'eligible',
    ];

    protected $casts = [
        'eligible' => 'boolean',
    ];

    public function collecte()
    {
        return $this->belongsTo(Collecte::class);
    }
}
