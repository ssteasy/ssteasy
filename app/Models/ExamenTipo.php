<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamenTipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
    ];

    public function profesiogramas()
    {
        return $this->belongsToMany(
            Profesiograma::class,
            'profesiograma_examen_tipo',
        )
        ->withPivot('periodicidad')
        ->withTimestamps();
    }
}
