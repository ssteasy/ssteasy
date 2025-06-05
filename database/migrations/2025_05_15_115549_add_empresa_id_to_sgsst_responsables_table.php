<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sgsst_responsables', function (Blueprint $table) {
            $table->foreignId('empresa_id')
                ->after('user_id')
                ->nullable() // Primero como nullable para evitar errores si ya hay datos
                ->constrained('empresas')
                ->cascadeOnDelete();
        });

        // Opcional: llenar los datos existentes con empresa_id del usuario
        \App\Models\SgsstResponsable::with('user')->get()->each(function ($responsable) {
            $responsable->empresa_id = $responsable->user->empresa_id ?? null;
            $responsable->save();
        });

        // Hacerlo no-nullable después de llenado
        Schema::table('sgsst_responsables', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('sgsst_responsables', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
        });
    }
};