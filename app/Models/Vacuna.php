<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
    ];

    public function profesiogramas()
    {
        return $this->belongsToMany(
            Profesiograma::class,
            'profesiograma_vacuna',
        )
        ->withTimestamps();
    }
}
