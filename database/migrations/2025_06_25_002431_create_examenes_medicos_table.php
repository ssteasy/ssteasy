<?php
// database/migrations/xxxx_xx_xx_create_examenes_medicos_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamenesMedicosTable extends Migration
{
    public function up()
    {
        Schema::create('examenes_medicos', function (Blueprint $table) {
            $table->id();                                            // Código del examen médico :contentReference[oaicite:8]{index=8}
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Documento del colaborador (usuario) :contentReference[oaicite:9]{index=9}
            $table->foreignId('profesiograma_examen_tipo_id')
                  ->constrained('profesiograma_examen_tipo')
                  ->cascadeOnDelete();                              // Sirve para tipificación, periodicidad y tipo de examen :contentReference[oaicite:10]{index=10}
            $table->date('fecha_examen');                            // Fecha del examen :contentReference[oaicite:11]{index=11}
            $table->enum('tipificacion', ['Ingreso','Egreso','Periódico','Post Incapacidad']);
            $table->date('fecha_siguiente')->nullable();             // Sugerida para próximo control :contentReference[oaicite:12]{index=12}
            $table->enum('concepto_medico', ['Apto','Apto con restricciones','No apto']);
            $table->text('recomendaciones')->nullable();             // Recomendaciones médicas :contentReference[oaicite:13]{index=13}
            $table->json('adjuntos')->nullable();                    // Adjuntos PDF imágenes :contentReference[oaicite:14]{index=14}
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('examenes_medicos');
    }
}

