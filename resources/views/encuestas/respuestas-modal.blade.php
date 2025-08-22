<x-filament::card>
    <table class="w-full divide-y divide-gray-200">
        <thead>
            <tr class="bg-gray-50">
                <th class="px-4 py-2 text-left text-sm font-medium">Pregunta</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Respuesta</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($encuesta->preguntas as $index => $pregunta)
                <tr>
                    <td class="px-4 py-2 text-sm">{{ $pregunta['enunciado'] }}</td>
                    <td class="px-4 py-2 text-sm">{{ $respuestas[$index] ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament::card>
