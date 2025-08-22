@props(['record'])

<x-filament::page>
    <x-filament::card>
        <h2 class="text-lg font-bold mb-4">Detalles del Comité</h2>

        <p><strong>Objetivo:</strong> {{ $record->objetivo ?? 'Sin descripción' }}</p>

        <p><strong>Inscripción:</strong>
            {{ $record->fecha_inicio_inscripcion->format('d/m/Y') }}
            —
            {{ $record->fecha_fin_inscripcion->format('d/m/Y') }}
        </p>

        <p><strong>Votación:</strong>
            {{ $record->fecha_inicio_votaciones->format('d/m/Y') }}
            —
            {{ $record->fecha_fin_votaciones->format('d/m/Y') }}
        </p>

        <p><strong>Miembros postulados:</strong> {{ $record->members_count }}</p>
    </x-filament::card>
</x-filament::page>
