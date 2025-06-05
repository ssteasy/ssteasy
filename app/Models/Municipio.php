<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    public function departamento() { return $this->belongsTo(Departamento::class, 'departamento_codigo', 'codigo'); }
}