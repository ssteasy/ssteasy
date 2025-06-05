<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/xxxx_xx_xx_add_segundo_nombre_and_segundo_apellido_to_users_table.php
return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('segundo_nombre')->nullable()->after('primer_nombre');
            $table->string('segundo_apellido')->nullable()->after('primer_apellido');
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['segundo_nombre', 'segundo_apellido']);
        });
    }
};

