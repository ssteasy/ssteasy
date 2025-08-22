<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProfesiogramaExamenTipo extends Pivot
{
    protected $table = 'profesiograma_examen_tipo';

    protected $fillable = [
        'profesiograma_id',
        'examen_tipo_id',
        'tipificacion',
        'periodicidad_valor',
        'periodicidad_unidad',
    ];

    /**
     * Relación al modelo Profesiograma,
     * que ya tiene el scope de empresa aplicado.
     */
    public function profesiograma(): BelongsTo
    {
        return $this->belongsTo(Profesiograma::class);
    }

    /**
     * Relación al catálogo de tipos de examen.
     */
    public function examenTipo(): BelongsTo
    {
        return $this->belongsTo(ExamenTipo::class);
    }
}
