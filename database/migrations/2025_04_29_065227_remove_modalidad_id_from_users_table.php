<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // simplemente eliminamos la columna (si existe)
        if (Schema::hasColumn('users', 'modalidad_id')) {
            $table->dropColumn('modalidad_id');
        }
    });
}

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('modalidad_id')->nullable()->constrained('modalidades')->nullOnDelete();
        });
    }
};