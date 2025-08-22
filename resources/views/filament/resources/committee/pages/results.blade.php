{{-- resources/views/filament/resources/committee/pages/results.blade.php --}}
<x-filament::page>
    @php
        $data   = collect($this->getChartData());
        $labels = $data->pluck('name');
        $votes  = $data->pluck('votes');
    @endphp

    @if ($data->isEmpty())
        <x-filament::card>
            <p class="text-center text-gray-500">
                Aún no se han registrado votos para este comité.
            </p>
        </x-filament::card>
    @else
        <x-filament::card>
            <canvas id="resultsChart" class="w-full h-96"></canvas>
        </x-filament::card>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const ctx = document.getElementById('resultsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: 'Votos',
                            data:  @json($votes),
                        }],
                    },
                    options: {
                        scales: {
                            y: { beginAtZero: true }
                        }
                    },
                });
            });
        </script>
    @endif

    {{-- Formulario para seleccionar ganadores (visible sólo a admin, y tras fin de votaciones) --}}
    @if (
        auth()->user()->hasAnyRole(['admin', 'superadmin'])
        && now()->isAfter($this->record->fecha_fin_votaciones->endOfDay())
    )
        <x-filament-panels::form wire:submit="saveWinners" class="mt-8 space-y-4">
            {{ $this->form }}

            <x-filament::button type="submit">
                Guardar ganadores
            </x-filament::button>
        </x-filament-panels::form>
    @endif
</x-filament::page>
