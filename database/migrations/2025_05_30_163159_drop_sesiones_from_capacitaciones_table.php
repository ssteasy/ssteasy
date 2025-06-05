<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            $table->dropColumn('sesiones');
        });
    }

    public function down(): void
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            $table->longText('sesiones')->nullable()->after('objetivo');
        });
    }
};
