<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Cambiamos el enum para añadir 'masiva'
        DB::statement("
            ALTER TABLE `capacitaciones`
            MODIFY COLUMN `tipo_asignacion`
            ENUM('manual','abierta','obligatoria','masiva')
            NOT NULL DEFAULT 'manual'
        ");
    }

    public function down(): void
    {
        // Revertimos al enum original
        DB::statement("
            ALTER TABLE `capacitaciones`
            MODIFY COLUMN `tipo_asignacion`
            ENUM('manual','abierta','obligatoria')
            NOT NULL DEFAULT 'manual'
        ");
    }
};
