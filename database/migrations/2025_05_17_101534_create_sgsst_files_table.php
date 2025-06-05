<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('sgsst_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')
                  ->constrained('empresas')
                  ->cascadeOnDelete();
            $table->foreignId('uploaded_by')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->date('signature_deadline')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('sgsst_files');
    }
};
