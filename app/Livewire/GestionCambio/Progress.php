<?php

namespace App\Livewire\GestionCambio;

use Livewire\Component;

class Progress extends Component
{
    public GestionCambio $cambio;

    public function mount(GestionCambio $cambio)
    {
        $this->cambio = $cambio->loadCount([
            'actividades as ejecutadas_count' =>
                fn ($q) => $q->whereDate('fecha_ejecucion','<=',now()),
        ]);
    }

    public function render()
    {
        $total  = $this->cambio->actividades()->count();
        $done   = $this->cambio->ejecutadas_count;
        $pct    = $total ? round(($done/$total)*100) : 0;

        return view('livewire.gestion-cambio.progress', compact('pct'));
    }
}
