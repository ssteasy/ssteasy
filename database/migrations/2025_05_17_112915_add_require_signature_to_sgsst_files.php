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
        Schema::table('sgsst_files', function (Blueprint $table) {
            $table->boolean('require_signature')
                  ->default(false)
                  ->after('file_path');
            //  signature_deadline se mantiene nullable; ya no será “required”
            $table->date('signature_deadline')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sgsst_files', function (Blueprint $table) {
            $table->dropColumn('require_signature');
            // opcional: volver a NOT NULL si quieres
        });
    }
};
