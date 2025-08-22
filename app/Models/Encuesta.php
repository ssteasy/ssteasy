<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    BelongsToMany
};

class Encuesta extends Model
{
    use HasFactory;

    protected $table = 'encuestas';

    /**
     * Los atributos que pueden asignarse masivamente.
     */
    protected $fillable = [
        'empresa_id',
        'capacitacion_id',
        'codigo',
        'nombre',
        'activa',
        'preguntas', // JSON con el array de preguntas
    ];

    /**
     * Casteos de atributos.
     */
    protected $casts = [
        'activa'    => 'boolean',
        'preguntas' => 'array', // se serializa/deserializa automáticamente
    ];

    /* ------------------------------------------------------------------------
     | Relaciones
     * ----------------------------------------------------------------------- */

    /**
     * La empresa a la que pertenece esta encuesta.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * La capacitación asociada a esta encuesta.
     */
    public function capacitacion(): BelongsTo
    {
        return $this->belongsTo(Capacitacion::class);
    }

    /**
     * Usuarios asignados a esta encuesta (pivot).
     */
    public function usuarios(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'encuesta_user')
            ->withPivot(['respuestas', 'respondida', 'respondido_at'])
            ->withTimestamps();
    }
    
}
