<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('encuesta_user', function (Blueprint $table) {
            $table->json('respuestas')->nullable()->after('user_id');
            $table->boolean('respondida')->default(false)->after('respuestas');
            $table->timestamp('respondido_at')->nullable()->after('respondida');
        });
    }

    public function down(): void
    {
        Schema::table('encuesta_user', function (Blueprint $table) {
            $table->dropColumn(['respuestas', 'respondida', 'respondido_at']);
        });
    }
};
