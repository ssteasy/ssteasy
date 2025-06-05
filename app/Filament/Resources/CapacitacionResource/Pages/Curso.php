<?php

namespace App\Filament\Resources\CapacitacionResource\Pages;

use App\Filament\Resources\CapacitacionResource;
use App\Models\Capacitacion;
use App\Models\Sesion;
use App\Models\Certificado;
use App\Services\CertificadoService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class Curso extends Page
{
    protected static ?string $navigationLabel = null;
    protected static ?string $navigationIcon = null;
    protected static string $resource = CapacitacionResource::class;
    protected static string $view = 'filament.resources.capacitacion-resource.pages.curso';

    public Capacitacion $record;
    public ?Sesion $currentSession = null;
    public array $answers = [];
    public ?int $score = null;

    public function mount(Capacitacion $record): void
    {
        abort_unless(Auth::user()->can('view', $record), 403);
        $this->record = $record;
    }

    public function getProgresoProperty(): int
    {
        $user = Auth::user();

        $total = $this->record->sesiones()->count();
        $aprobadas = $this->record->sesiones()
            ->whereHas(
                'usuarios',
                fn($q) =>
                $q->where('user_id', $user->id)
                    ->where('aprobado', true)
            )->count();

        return $total ? intval(($aprobadas / $total) * 100) : 0;
    }

    public function getYaTieneCertificadoProperty(): bool
    {
        $user = Auth::user();

        return Certificado::where('user_id', $user->id)
            ->where('capacitacion_id', $this->record->id)
            ->exists();
    }

    public function getPuedeCertificarProperty(): bool
    {
        $user = Auth::user();
        $progreso = $this->getProgresoProperty();

        return $progreso === 100;
    }

    /**
     * Devuelve la fila pivot sesion_user para el usuario actual y la sesión $sesionId.
     * Si no existe, retorna null.
     */
    protected function getPivotFor(int $sesionId)
    {
        $userId = Auth::id();
        return Sesion::find($sesionId)
                ?->usuarios()
            ->wherePivot('user_id', $userId)
            ->first()?->pivot;
    }

    /**
     * Invocado por Livewire cuando el colaborador hace clic en “Responder” o “Terminar sesión”.
     * Bloquea si ya existe completado_at en el pivot (a menos que haya sido reiniciado por admin).
     */
    public function viewSession(int $sesionId): void
    {
        $pivot = $this->getPivotFor($sesionId);

        // Si ya existe completado_at y aprobado (o completado), no permitimos reingresar:
        if ($pivot && $pivot->completado_at !== null) {
            Notification::make()
                ->warning()
                ->title('Sesión Ya Completada')
                ->body('Esta sesión ya fue completada. Contacta al administrador si necesitas reintentarlo.')
                ->send();

            return;
        }

        // Si no existe pivot o no está completado, abrimos la sesión:
        $this->currentSession = Sesion::findOrFail($sesionId);

        // Recoger posible nota previa (en caso de reintentos permitidos por admin)
        $this->score = optional(
            $this->currentSession
                ->usuarios
                ->firstWhere('id', Auth::id())
        )->pivot->score ?? null;

        // Inicializar array de respuestas al valor en BD (si existe) o vacío:
        $this->answers = [];
        foreach ($this->currentSession->preguntas ?? [] as $i => $_) {
            $this->answers[$i] = $pivot?->respuesta_json
                ? json_decode($pivot->respuesta_json, true)[$i] ?? null
                : null;
        }
    }

    /**
     * Procesar las respuestas de la sesión. 
     * Solo se guarda si el administrador no ha bloqueado el campo.
     */
    public function submitAnswers(): void
    {
        if (!$this->currentSession) {
            return;
        }

        $pivot = $this->getPivotFor($this->currentSession->id);

        // 1) Si ya existe completado_at, no dejamos guardar de nuevo
        if ($pivot && $pivot->completado_at !== null) {
            Notification::make()
                ->warning()
                ->title('No autorizado')
                ->body('Esta sesión ya está finalizada. Sólo un administrador puede desbloquearla.')
                ->send();

            return;
        }

        // 2) Calcular la nota
        $pregs = $this->currentSession->preguntas;
        $correctas = 0;
        $totalEval = 0;

        foreach ($pregs as $i => $p) {
            if ($p['tipo'] === 'abierta') {
                continue;
            }
            $totalEval++;
            $resp = $this->answers[$i] ?? null;

            if ($p['tipo'] === 'vf') {
                if ((bool) $resp === (bool) $p['correcto']) {
                    $correctas++;
                }
            } elseif ($p['tipo'] === 'unica') {
                $idxCorrecta = collect($p['opciones'])
                    ->search(fn($o) => $o['correcta']);
                if ((int) $resp === $idxCorrecta) {
                    $correctas++;
                }
            }
        }

        $nota = $totalEval
            ? round(($correctas / $totalEval) * 100)
            : 0;

        // 3) Guardar o actualizar el pivote con completado_at = now()
        $this->currentSession->usuarios()->syncWithoutDetaching([
            Auth::id() => [
                'aprobado' => $nota >= 80,
                'score' => $nota,
                'respuesta_json' => json_encode($this->answers),
                'completado_at' => now(),
            ],
        ]);

        // 4) Forzar recarga de la relación “usuarios” en este Sesion
        $this->currentSession->load('usuarios');

        // 5) Forzar recarga de todas las sesiones del curso (para que el listado inferior se actualice)
        $this->record->load('sesiones.usuarios');

        // 6) (Opcional) Para que el formulario desaparezca inmediatamente, descomenta esta línea:
//  $this->currentSession = null;

        $this->score = $nota;

        Notification::make()
            ->success()
            ->title("Obtuviste {$nota}% en esta sesión.")
            ->send();
    }


    /**
     * Marcar la sesión como completada (sin evaluación). 
     * Bloquea la sesión para no permitir respuestas posteriores.
     */
    public function terminateSession(): void
    {
        if (!$this->currentSession) {
            return;
        }

        $pivot = $this->getPivotFor($this->currentSession->id);
        if ($pivot && $pivot->completado_at !== null) {
            Notification::make()
                ->warning()
                ->title('No autorizado')
                ->body('Esta sesión ya está finalizada. Sólo un administrador puede desbloquearla.')
                ->send();

            return;
        }

        $this->currentSession->usuarios()->syncWithoutDetaching([
            Auth::id() => [
                'aprobado' => true,
                'score' => null,
                'respuesta_json' => null,
                'completado_at' => now(),
            ],
        ]);

        // Forzar recarga de la relación “usuarios”
        $this->currentSession->load('usuarios');

        Notification::make()
            ->success()
            ->title('Sesión completada')
            ->send();

        // Cerramos la vista actual y volvemos al listado
        $this->currentSession = null;
    }

    /**
     * Acción Livewire opcional para que el administrador “desbloquee” una sesión
     * para un usuario dado.
     */
    public function desbloquearSesion(int $sesionId, int $userId): void
    {
        // Sólo permitimos que un admin lo haga:
        $u = Auth::user();
        if (!$u->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        $sesion = Sesion::findOrFail($sesionId);
        $sesion->usuarios()->updateExistingPivot($userId, [
            'aprobado' => false,
            'score' => null,
            'respuesta_json' => null,
            'completado_at' => null,
        ]);

        Notification::make()
            ->success()
            ->title("Sesión reabierta para el usuario.")
            ->send();

        // Si quieres recargar también la vista actual en caso de que el admin
        // esté desbloqueando desde esta misma página:
        if ($this->currentSession && $this->currentSession->id === $sesionId) {
            $this->currentSession->load('usuarios');
        }
    }
    // Curso.php  – dentro de la clase

    public function descargarCertificado()   //  ← sin “: \Livewire\Redirector”
    {
        if (!$this->getPuedeCertificarProperty()) {
            Notification::make()
                ->danger()
                ->title('Aún no has completado el curso')
                ->body('Debes aprobar todas las sesiones para obtener tu certificado.')
                ->send();

            return;   // basta con no redirigir
        }

        $certificado = app(\App\Services\CertificadoService::class)
            ->generarPara(Auth::user(), $this->record);

        return redirect(
            Storage::disk('public')->url($certificado->file_path)
        );
    }
}
