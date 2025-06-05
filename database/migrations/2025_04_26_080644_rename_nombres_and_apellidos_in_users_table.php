<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('nombres', 'primer_nombre');
            $table->renameColumn('apellidos', 'primer_apellido');
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('primer_nombre', 'nombres');
            $table->renameColumn('primer_apellido', 'apellidos');
        });
    }
};
