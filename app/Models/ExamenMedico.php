<?php
// app/Models/ExamenMedico.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ExamenMedico extends Model
{ 
    protected $table = 'examen_medicos';

    protected $fillable = [
        'empresa_id',
        'user_id',
        'profesiograma_examen_tipo_id',
        'fecha_examen',
        'tipificacion',
        'fecha_siguiente',
        'concepto_medico',
        'recomendaciones',
        'adjuntos',
    ];

    protected $casts = [
        'fecha_examen'    => 'date',
        'fecha_siguiente' => 'date',
        'adjuntos'        => 'array',
    ];

    protected static function booted()
    {
        static::creating(function (ExamenMedico $exam) {
            $exam->empresa_id = auth()->user()->empresa_id;

            if ($exam->tipificacion === 'Periódico') {
                $pivot = $exam->profesiogramaExamenTipo;
                $unit  = $pivot->periodicidad_unidad;   // "días","meses","años"
                $value = $pivot->periodicidad_valor;

                // Mapea al método Carbon correcto
                $method = match ($unit) {
                    'días'  => 'addDays',
                    'meses' => 'addMonths',
                    'años'  => 'addYears',
                    default => null,
                };

                if ($method) {
                    $exam->fecha_siguiente = Carbon::parse($exam->fecha_examen)
                        ->{$method}($value);
                }
            }
        });

        static::addGlobalScope('empresa', function (Builder $query) {
            $query->where('empresa_id', auth()->user()->empresa_id);
        });
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profesiogramaExamenTipo(): BelongsTo
    {
        return $this->belongsTo(ProfesiogramaExamenTipo::class);
    }
}
