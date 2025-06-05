<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nombres');
            $table->string('apellidos');
            $table->enum('tipo_documento', ['Cédula de ciudadanía', 'Cédula extranjera', 'Registro civil', 'Tarjeta de identidad', 'NIT', 'Pasaporte']);
            $table->string('numero_documento');
            $table->enum('sexo', ['Masculino', 'Femenino', 'Otro']);
            
            $table->string('telefono');
            $table->string('direccion');
            $table->string('pais_dane');
            $table->string('departamento_dane');
            $table->string('municipio_dane');
            $table->enum('zona', ['Rural', 'Urbana']);
        
            $table->foreignId('cargo_id');
            $table->foreignId('rol_personalizado_id')->nullable();
        
            $table->enum('tipo_contrato', ['Fijo', 'Indefinido', 'Aprendizaje', 'Pensionado', 'Obra labor', 'Temporal']);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->enum('modalidad', ['Presencial', 'Teletrabajo', 'Trabajo Remoto', 'Trabajo en casa']);
            $table->enum('nivel_riesgo', ['Riesgo I', 'Riesgo II', 'Riesgo III', 'Riesgo IV']);
        
            $table->string('sede');
            $table->string('centro_trabajo');
        
            $table->string('eps');
            $table->string('ips')->nullable();
            $table->string('arl');
            $table->string('afp');
        
            // Ya debe tener empresa_id + password + email
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
