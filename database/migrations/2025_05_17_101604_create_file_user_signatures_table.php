<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up(): void {
        Schema::create('file_user_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sgsst_file_id')
                  ->constrained('sgsst_files')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
            $table->unique(['sgsst_file_id','user_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('file_user_signatures');
    }
};
