<?php
// app/Models/Epp.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Epp extends Model
{
    // Nombre de la tabla por convención es "epps"
    protected $fillable = [
        'codigo',
        'nombre',
        'foto',
        'codigo_barras',
        'observaciones',
    ];

    /**
     * Pivot EppCargo: donde guardamos periodicidad, cantidad, reposición...
     */
    public function eppCargos(): HasMany
    {
        return $this->hasMany(EppCargo::class);
    }

    /**
     * Relación directa a cargos a través del pivot.
     * Con withPivot() exponemos todos los campos extra.
     */
    public function cargos(): BelongsToMany
    {
        return $this->belongsToMany(Cargo::class, 'epp_cargo')
                    ->using(EppCargo::class)
                    ->withPivot([
                        'periodicidad_valor',
                        'periodicidad_unidad',
                        'cantidad',
                        'reposicion_valor',
                        'reposicion_unidad',
                    ])
                    ->withTimestamps();
    }
}
