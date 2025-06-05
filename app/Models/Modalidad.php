<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Modalidad extends Model
{
    use HasFactory;

    // Forzamos la tabla correcta
    protected $table = 'modalidades';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'activo',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
