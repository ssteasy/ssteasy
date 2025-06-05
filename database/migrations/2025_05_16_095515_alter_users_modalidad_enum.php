<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;  

return new class extends Migration {
    public function up(): void
    {
        // Aquí definimos sólo PRESENCIAL, HIBRIDO y TRABAJO REMOTO
        DB::statement("
            ALTER TABLE `users`
            MODIFY `modalidad`
            ENUM('Presencial','Hibrido','Trabajo Remoto')
            NOT NULL
            DEFAULT 'Presencial'
        ");
    }

    public function down(): void
    {
        // Volver al enum original con Teletrabajo y Trabajo en casa
        DB::statement("
            ALTER TABLE `users`
            MODIFY `modalidad`
            ENUM('Presencial','Teletrabajo','Trabajo Remoto','Trabajo en casa')
            NOT NULL
            DEFAULT 'Presencial'
        ");
    }
};