<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Committee extends Model {
    use HasFactory;


    protected $fillable = [
        'nombre',
        'objetivo',
        'fecha_inicio_inscripcion',
        'fecha_fin_inscripcion',
        'fecha_inicio_votaciones',
        'fecha_fin_votaciones',
        'empresa_id' // Asegúrate de incluir este campo
    ];

    protected $casts = [
        'fecha_inicio_votaciones' => 'date',
        'fecha_fin_votaciones' => 'date',
        'fecha_inicio_inscripcion' => 'date',
        'fecha_fin_inscripcion' => 'date',
    ];

    public function members() {
        return $this->hasMany(CommitteeMember::class);
    }

    public function empresa() {
        return $this->belongsTo(Empresa::class);
    }
    public function votes() { return $this->hasMany(Vote::class); }
}