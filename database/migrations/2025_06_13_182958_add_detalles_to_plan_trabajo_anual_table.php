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
        Schema::table('plan_trabajo_anual', function (Blueprint $table) {
            $table->longText('roles_responsabilidades')->nullable()->after('year');
            $table->longText('recursos')->nullable()->after('roles_responsabilidades');
            $table->longText('objetivo')->nullable()->after('recursos');
            $table->longText('alcance')->nullable()->after('objetivo');
        });
    }

    public function down(): void
    {
        Schema::table('plan_trabajo_anual', function (Blueprint $table) {
            $table->dropColumn([
                'roles_responsabilidades',
                'recursos',
                'objetivo',
                'alcance',
            ]);
        });
    }
};
