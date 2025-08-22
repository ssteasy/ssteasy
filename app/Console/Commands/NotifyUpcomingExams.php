<?php
// app/Console/Commands/NotifyUpcomingExams.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExamenMedico;
use App\Notifications\UpcomingExamNotification;
use Carbon\Carbon;

class NotifyUpcomingExams extends Command
{
    protected $signature = 'examenes:notify';
    protected $description = 'Notificar exámenes a un mes de vencer';

    public function handle()
    {
        $hoy   = Carbon::today();
        $um   = $hoy->copy()->addMonth();
        $exams = ExamenMedico::whereBetween('fecha_siguiente', [$hoy, $um])->get();

        foreach ($exams as $exam) {
            $exam->usuario->notify(new UpcomingExamNotification($exam));
        }
    }
}
