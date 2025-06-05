<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rol extends Model
{
    protected $fillable = [
        'empresa_id',
        'cargo_id',
        'nombre',
        'activo',
    ];

    // Relación al cargo al que pertenece este rol
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    // (Opcional) Relación a la empresa propietaria
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}