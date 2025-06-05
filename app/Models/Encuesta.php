<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Encuesta extends Model
{
    use HasFactory;

    protected $table = 'encuestas';

    protected $fillable = [
        'empresa_id',
        'capacitacion_id',
        'codigo',
        'nombre',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    /* Relaciones ------------------------------------------------------------------- */

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function capacitacion(): BelongsTo
    {
        return $this->belongsTo(Capacitacion::class);
    }

    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class);
    }
}
