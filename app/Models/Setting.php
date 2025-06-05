<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// app/Models/Setting.php
class Setting extends Model
{
    protected $fillable = ['key', 'value'];
    public $timestamps = false;

    /** tiny helper */
    public static function get(string $key, $default = null)
    {
        return optional(static::firstWhere('key', $key))->value ?? $default;
    }
    public static function put(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
