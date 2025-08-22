<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];

        foreach ($meses as $mes) {
            DB::statement("
                ALTER TABLE `plan_actividades`
                MODIFY COLUMN `mes_{$mes}` 
                ENUM('planear','pospuesta','ejecutada','no_aplica')
                NOT NULL 
                DEFAULT 'no_aplica'
            ");
        }
    }

    public function down(): void
    {
        $meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];

        foreach ($meses as $mes) {
            DB::statement("
                ALTER TABLE `plan_actividades`
                MODIFY COLUMN `mes_{$mes}` 
                ENUM('planear','pospuesta','ejecutada')
                NOT NULL 
                DEFAULT 'planear'
            ");
        }
    }
};
