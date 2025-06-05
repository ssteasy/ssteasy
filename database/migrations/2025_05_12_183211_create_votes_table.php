<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('committee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('committee_member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('voter_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // Un mismo usuario solo puede votar una vez por comité
            $table->unique(['committee_id', 'voter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
