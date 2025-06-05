<?php
// app/Models/Sede.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

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
