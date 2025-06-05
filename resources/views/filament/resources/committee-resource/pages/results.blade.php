<x-filament::page>
    <div class="p-6 bg-white rounded-lg shadow">
        {{-- Título --}}
        <h2 class="text-2xl font-bold mb-6">
            Resultados del Comité: {{ $this->record->nombre }}
        </h2>

        {{-- Gráfico --}}
        <div class="w-full md:w-3/4 mx-auto mb-8">
            <canvas id="resultsChart" height="200"></canvas>
        </div>

        {{-- Tabla de resultados --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">Candidato</th>
                        <th class="px-4 py-2">Votos</th>
                        <th class="px-4 py-2">Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->getData()['labels'] as $index => $label)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $label }}</td>
                            <td class="px-4 py-2 text-center">
                                {{ $this->getData()['datasets'][0]['data'][$index] }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                @php
    $data = $this->getData();
    $votes = $data['datasets'][0]['data'][$index];
    $total = array_sum($data['datasets'][0]['data']);
    $percentage = ($votes / $total) * 100;
@endphp

{{ number_format($percentage, 2) }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('resultsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: @json($this->getData()),
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-filament::page>