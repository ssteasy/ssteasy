{{-- resources/views/filament/resources/committee/pages/members.blade.php --}}
@php use Illuminate\Support\Facades\Storage; @endphp

<x-filament::page class="max-w-7xl mx-auto px-6 py-8">
    <h2 class="text-3xl font-bold mb-6">
        Miembros activos de “{{ $this->record->nombre }}”
    </h2>

    @php
        $winners = $this->record
            ->members()
            ->where('activo', true)
            ->with(['user.cargo', 'user.sede'])
            ->get();
    @endphp

    @if($winners->isEmpty())
        <x-filament::card>
            <p class="text-center text-gray-500">
                Aún no se han marcado ganadores para este comité.
            </p>
        </x-filament::card>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($winners as $member)
                @php
                    $user     = $member->user;
                    $photoUrl = $member->foto
                        ? Storage::url($member->foto)
                        : ($user->profile_photo_path
                            ? Storage::url($user->profile_photo_path)
                            : asset('default-avatar.png'));
                @endphp

                <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow p-6 text-center">
                    <img
                        src="{{ $photoUrl }}"
                        alt="Foto de {{ $user->primer_nombre }}"
                        class="mx-auto w-20 h-20 rounded-full object-cover mb-4"
                    />

                    <h3 class="text-lg font-semibold mb-1">
                        {{ $user->primer_nombre }} {{ $user->primer_apellido }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-3">
                        {{ $user->cargo->nombre ?? 'Sin cargo' }}
                    </p>

                    <div class="space-y-1 text-sm text-gray-600">
                        <p><strong>Sede:</strong> {{ $user->sede->nombre ?? '–' }}</p>
                        <p><strong>Rol:</strong> {{ $member->rol_en_comite ?? '–' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-filament::page>
