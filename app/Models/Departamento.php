<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    public function pais() { return $this->belongsTo(Pais::class, 'pais_codigo', 'codigo'); }
    public function municipios() { return $this->hasMany(Municipio::class, 'departamento_codigo', 'codigo'); }
}
