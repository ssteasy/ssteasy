<x-filament::page>
    @php
        $data   = collect($this->getChartData());
        $labels = $data->pluck('name');
        $votes  = $data->pluck('votes');
    @endphp

    {{-- DEBUG --}}
    <x-filament::card class="mb-6">
        <p class="font-semibold">Debug – datos que llegan del backend:</p>
        <pre class="text-xs bg-gray-100 p-2 rounded">{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
    </x-filament::card>

    {{-- Si llegan vacíos muestra aviso, si no, grafica --}}
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

        {{-- Carga Chart.js desde CDN solo para esta página --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                console.log('Labels →', @json($labels));
                console.log('Votes  →', @json($votes));

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
                    }
                });
            });
        </script>
    @endif
</x-filament::page>
