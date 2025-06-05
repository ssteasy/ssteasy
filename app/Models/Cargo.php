<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    // Asegúrate de incluir empresa_id aquí
    protected $fillable = [
        'empresa_id',
        'codigo',
        'nombre',
        'activo',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
