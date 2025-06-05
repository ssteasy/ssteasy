<?php

namespace App\Services;

use App\Models\Certificado;
use App\Models\Capacitacion;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CertificadoService
{
    /**
     * Genera (y guarda) el PDF de certificado para $user y $curso.
     * Devuelve la instancia de Certificado creada o existente.
     *
     * @param  User         $user
     * @param  Capacitacion $curso
     * @return Certificado
     */
    public function generarPara(User $user, Capacitacion $curso): Certificado
    {
        // 1) Si ya existe registro en BD, lo retornamos (sin volver a generar)
        $existente = Certificado::where('user_id', $user->id)
            ->where('capacitacion_id', $curso->id)
            ->first();

        if ($existente) {
            return $existente;
        }

        // 2) Generar un código único
        $codigoUnico = Str::uuid()->toString();

        // 3) Obtener datos para la vista Blade
        $empresa = $user->empresa; // asegúrate de que User->empresa() exista y devuelva el modelo Empresa
        $logoPath = asset(Storage::url($empresa->logo_path)); // si tu modelo Empresa guarda 'logo_path' en disco

        $porcentaje = $this->calcularPorcentajeCurso($user, $curso);
        $hoy = Carbon::now();

        // 4) Renderizar la vista Blade a PDF (Dompdf)
        $pdf = Pdf::loadView('certificados.template', [
            'user'        => $user,
            'curso'       => $curso,
            'porcentaje'  => $porcentaje,
            'hoy'         => $hoy,
            'logoPath'    => $logoPath,
            'codigoUnico' => $codigoUnico,
        ]);

        // 5) Definir ruta relativa dentro de “storage/app/public”
        $carpetaEmpresa = 'certificados/empresa_' . $empresa->id;
        $carpetaCurso   = $carpetaEmpresa . '/curso_' . $curso->id;
        $filename       = 'certificado_user_' . $user->id . '_curso_' . $curso->id . '.pdf';
        $rutaPublica    = $carpetaCurso . '/' . $filename;
        // Ejemplo final: “certificados/empresa_1/curso_7/certificado_user_4_curso_7.pdf”

        // 6) Asegurarnos de que exista la carpeta en disco “public”
        Storage::disk('public')->makeDirectory($carpetaCurso);

        // 7) Guardar el PDF en el disco “public”
        Storage::disk('public')->put($rutaPublica, $pdf->output());

        // 8) Insertar registro en la tabla “certificados”
        $certificado = Certificado::create([
            'user_id'         => $user->id,
            'capacitacion_id' => $curso->id,
            'codigo_unico'    => $codigoUnico,
            'file_path'       => $rutaPublica,
        ]);

        return $certificado;
    }

    /**
     * Calcula el porcentaje promedio de todas las sesiones aprobadas del $user
     * en el $curso.
     */
    protected function calcularPorcentajeCurso(User $user, Capacitacion $curso): int
    {
        $totalSesiones = $curso->sesiones()->count();
        if ($totalSesiones === 0) {
            return 0;
        }

        $aprobadas = $curso->sesiones()
            ->whereHas('usuarios', fn($q) =>
                $q->where('user_id', $user->id)
                  ->where('aprobado', true)
            )
            ->count();

        return intval(($aprobadas / $totalSesiones) * 100);
    }
}
