<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class Respuesta extends Model
{
    use HasFactory;

    protected $table = 'respuestas';

    protected $fillable = [
        'pregunta_id',
        'user_id',
        'respuesta',
    ];

    /* Relaciones ------------------------------------------------------------------- */

    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
