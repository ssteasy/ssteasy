<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'sede')) {
                $table->dropColumn('sede');
            }
            if (Schema::hasColumn('users', 'centro_trabajo')) {
                $table->dropColumn('centro_trabajo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('sede')->nullable()->after('nivel_riesgo');
            $table->string('centro_trabajo')->nullable()->after('sede');
        });
    }
};