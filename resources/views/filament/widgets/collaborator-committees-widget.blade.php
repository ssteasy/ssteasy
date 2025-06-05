<x-filament::section>
    <x-slot name="heading">Mis Comités</x-slot>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($committees as $data)
            @php
                /** @var \App\Models\Committee $c */
                $c = $data['c'];
            @endphp

            <x-filament::card class="space-y-3">
                <h3 class="font-semibold">{{ $c->nombre }}</h3>

                @if ($data['stage'] === 'inscripcion')
                    <p class="text-sm text-gray-600">Inscripción abierta</p>

                    @if ($data['yaInscrito'])
                        <x-filament::badge color="success">Ya inscrito</x-filament::badge>
                    @else
                        <x-filament::button
                            wire:click="mountAction('inscribirse', {{ $c->id }})"
                            size="sm"
                            icon="heroicon-o-user-plus"
                        >
                            Inscribirme
                        </x-filament::button>
                    @endif

                @elseif ($data['stage'] === 'votacion')
                    <p class="text-sm text-gray-600">
                        Votación en curso ({{ $c->votes->count() }} votos)
                    </p>

                    @if ($data['yaVoto'])
                        <x-filament::badge color="success">Ya votaste</x-filament::badge>
                    @else
                        <x-filament::button
                            tag="a"
                            :href="route('filament.admin.resources.committees.vote', $c->id)"
                            size="sm"
                            icon="heroicon-o-pencil-square"
                        >
                            Votar
                        </x-filament::button>
                    @endif

                @else
                    <p class="text-sm text-gray-600">
                        Ganador: {{ $data['winner']?->user?->full_name ?? 'Sin votos' }}
                    </p>
                @endif
            </x-filament::card>
        @endforeach
    </div>
</x-filament::section>
