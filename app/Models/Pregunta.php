<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Pregunta extends Model
{
    protected $table = 'sesion_preguntas';

    protected $fillable = [
        'sesion_id',
        'tipo',
        'enunciado',
        'peso',
    ];

    public function sesion()
    {
        return $this->belongsTo(Sesion::class);
    }

    public function opciones()
    {
        return $this->hasMany(Opcion::class);
    }
}
