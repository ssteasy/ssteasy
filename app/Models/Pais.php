<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'paises';       // <— obligamos el nombre plural
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    public function departamentos()
    {
        return $this->hasMany(Departamento::class, 'pais_codigo', 'codigo');
    }
}
