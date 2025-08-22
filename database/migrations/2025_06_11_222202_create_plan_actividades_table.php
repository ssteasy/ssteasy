<?php

// database/migrations/2025_06_11_000001_create_plan_actividades_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plan_actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_trabajo_anual_id')
                  ->constrained('plan_trabajo_anual')
                  ->cascadeOnDelete();
            $table->text('actividad');
            $table->text('responsable');
            $table->text('alcance');
            $table->text('criterio');
            $table->text('observacion')->nullable();
            $table->text('frecuencia');

            // 12 meses como ENUM
            foreach (['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'] as $mes) {
                $table->enum("mes_{$mes}", ['planear','pospuesta','ejecutada'])
                      ->default('planear');
            }

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_actividades');
    }
};
