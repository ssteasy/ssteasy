{{-- resources/views/filament/resources/sgsst-responsable/pages/cards-list.blade.php --}}
@php
    $responsables = $this->responsables ?? $responsables ?? collect();
@endphp

<x-filament::page>
    <div class="space-y-4">
        <h2 class="text-2xl font-bold">Responsables SG-SST Activos</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($responsables as $r)
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="flex items-center space-x-4 mb-4">
                        <img src="{{ $r->user->profile_photo_url }}"
                             class="w-12 h-12 rounded-full object-cover" alt="Avatar">
                        <div>
                            <p class="font-semibold">{{ $r->user->primer_nombre }} {{ $r->user->primer_apellido }}</p>
                            <p class="text-sm text-gray-500">{{ $r->user->email }}</p>
                        </div>
                    </div>

                    <p class="text-sm mb-2"><strong>Teléfono:</strong> {{ $r->user->telefono }}</p>
                    <p class="text-sm mb-2"><strong>Funciones:</strong> {{ \Illuminate\Support\Str::limit($r->funciones, 60) }}</p>
                    <p class="text-sm mb-2"><strong>Desde:</strong> {{ $r->fecha_inicio->format('Y-m-d') }}</p>

                    @if($r->documentos)
                        <div class="mt-2">
                            <p class="font-semibold text-sm mb-1">Documentos:</p>
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach($r->documentos as $doc)
                                    <li>
                                        <a href="{{ Storage::url($doc['file']) }}"
                                           target="_blank"
                                           class="text-primary hover:underline">
                                            {{ $doc['titulo'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @empty
                <p>No hay responsables activos.</p>
            @endforelse
        </div>
    </div>
</x-filament::page>
