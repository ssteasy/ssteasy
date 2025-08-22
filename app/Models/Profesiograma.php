<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profesiograma extends Model
{
    protected $fillable = [
        'empresa_id',
        'cargo_id',
        'tareas',
        'funciones',
        'adjuntos',
    ];

    /**
     * Auto-cast del campo JSON adjuntos
     */
    protected $casts = [
        'adjuntos' => 'array',
    ];

    /**
     * Alcance global para multi-tenant
     */
    protected static function booted()
    {
        static::creating(function (Profesiograma $prof) {
            $prof->empresa_id = auth()->user()->empresa_id;
        });

        static::addGlobalScope('empresa', fn($q) =>
            $q->where('empresa_id', auth()->user()->empresa_id)
        );
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function examenes(): BelongsToMany
    {
        return $this->belongsToMany(
            ExamenTipo::class,
            'profesiograma_examen_tipo'
        )
        ->withPivot('periodicidad_valor', 'periodicidad_unidad')
        ->withTimestamps();
    }

    public function vacunas(): BelongsToMany
    {
        return $this->belongsToMany(
            Vacuna::class,
            'profesiograma_vacuna'
        )
        ->withTimestamps();
    }

    public function epps(): BelongsToMany
    {
        return $this->belongsToMany(
            Epp::class,
            'profesiograma_epp'
        )
        ->withTimestamps();
    }

    public function profesiogramaExamenTipos(): HasMany
    {
        return $this->hasMany(ProfesiogramaExamenTipo::class);
    }
    
}
