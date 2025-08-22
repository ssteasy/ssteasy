<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plan_trabajo_anual', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->cascadeOnDelete();
            $table->year('year');
            $table->timestamps();

            $table->unique(['empresa_id', 'year'], 'unique_plan_por_empresa_anio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_trabajo_anual');
    }
};