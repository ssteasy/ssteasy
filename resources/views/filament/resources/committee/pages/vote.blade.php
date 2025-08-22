@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-filament::page class="max-w-7xl mx-auto px-6 py-8">
    <h2 class="text-3xl font-bold mb-4">{{ $this->record->nombre }}</h2>
    <p class="text-gray-600 mb-10">{{ $this->record->objetivo }}</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($this->record->members()->with(['user.cargo','user.sede'])->get() as $member)
            @php
                $user       = $member->user;
                $isSelected = $choice === $member->id;
                $photoUrl   = $member->foto
                    ? Storage::url($member->foto)
                    : ($user->profile_photo_path
                        ? Storage::url($user->profile_photo_path)
                        : asset('default-avatar.png'));
            @endphp

            {{-- Card clickeable solo si NO ha votado --}}
            <div
                @if(! $alreadyVoted)
                    wire:click="$set('choice', {{ $member->id }})"
                @endif
                class="relative bg-white border rounded-xl p-6
                       shadow-md transition-shadow duration-200
                       {{ $isSelected ? 'ring-2 ring-indigo-500 border-indigo-200 shadow-lg' : 'border-gray-200 hover:shadow-lg' }}
                       {{ $alreadyVoted ? 'cursor-default opacity-80' : 'cursor-pointer' }}"
            >
                {{-- Indicador de selección --}}
                @if($isSelected)
                    <div class="absolute top-3 right-3 bg-indigo-500 text-white rounded-full p-1">
                        <x-heroicon-o-check class="w-5 h-5" />
                    </div>
                @endif

                <div class="flex items-center space-x-4 mb-4">
                    <img src="{{ $photoUrl }}"
                         alt="Foto de {{ $user->primer_nombre }}"
                         class="w-16 h-16 rounded-full object-cover" />

                    <div>
                        <h3 class="text-xl font-semibold leading-tight">
                            {{ $user->primer_nombre }} {{ $user->primer_apellido }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ $user->cargo->nombre ?? 'Sin cargo' }}
                        </p>
                    </div>
                </div>

                <div class="space-y-2 text-sm text-gray-700 mb-4">
                    <p><strong>Sede:</strong> {{ $user->sede->nombre ?? '–' }}</p>
                    <p><strong>Contacto:</strong> {{ $user->telefono ?? $user->email }}</p>
                </div>

                {{-- Radio deshabilitado si ya votó --}}
                <div class="flex items-center">
                    <input
                        type="radio"
                        wire:model="choice"
                        value="{{ $member->id }}"
                        @if($alreadyVoted) disabled @endif
                        class="form-radio h-5 w-5 text-indigo-600"
                    />
                    <span class="ml-2 text-sm font-medium">Seleccionar</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Botón Votar o Mensaje --}}
    <div class="mt-10 flex justify-end">
        @if(! $alreadyVoted)
            <x-filament::button wire:click="vote" color="primary">
                Votar
            </x-filament::button>
        @else
            <span class="text-gray-600 font-medium">
                Ya has emitido tu voto y no puedes cambiarlo.
            </span>
        @endif
    </div>
</x-filament::page>
