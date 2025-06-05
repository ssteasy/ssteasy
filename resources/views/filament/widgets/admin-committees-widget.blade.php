<x-filament::section>
    <x-slot name="heading">Comités – Panel Admin</x-slot>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($committees as $c)
            <x-filament::card class="space-y-3">
                <h3 class="font-semibold">{{ $c['nombre'] }}</h3>

                @if ($c['stage'] === 'inscripcion')
                    <p class="text-sm text-gray-600">
                        Inscripción abierta • Inscritos: {{ $c['inscritos'] }}
                    </p>
                @elseif ($c['stage'] === 'votacion')
                    <p class="text-sm text-gray-600">
                        Votación en curso • Votos emitidos: {{ $c['totalVotos'] }} /
                        {{ $c['empresaVotantes'] }}
                    </p>
                @else
                    <p class="text-sm text-gray-600">
                        Ganador: 
                        {{ $c['winner']?->user?->full_name ?? 'Sin votos' }}
                        ({{ $c['winner']?->votes->count() ?? 0 }} votos)
                    </p>
                @endif

                <x-filament::link
                    :href="route('filament.admin.resources.committees.edit', $c['id'])"
                    color="secondary"
                    size="sm"
                    icon="heroicon-o-arrow-top-right-on-square"
                >
                    Gestionar
                </x-filament::link>
            </x-filament::card>
        @endforeach
    </div>
</x-filament::section>
