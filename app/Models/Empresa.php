<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'nit',
        'email',
        'telefono',
        'direccion',
        'razon_social',
        'ciudad',
        'website',
        'logo',
        'activo',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function usuarios(): HasMany   // ← nombre en español
    {
        return $this->hasMany(User::class, 'empresa_id');
    }
}
