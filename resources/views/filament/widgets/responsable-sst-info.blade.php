<x-filament::widget>
    <x-filament::card>
        @if ($responsable)
            <div class="flex items-start space-x-4">
                <img
                    src="{{ $responsable->user->profile_photo_url }}"
                    alt="Foto"
                    class="w-24 h-24 rounded-full object-cover border"
                >

                <div class="space-y-1 text-sm">
                    <p><strong>Nombre:</strong> {{ $responsable->user->primer_nombre }} {{ $responsable->user->segundo_nombre }} {{ $responsable->user->primer_apellido }} {{ $responsable->user->segundo_apellido }}</p>
                    <p><strong>Documento:</strong> {{ $responsable->user->tipo_documento }} {{ $responsable->user->numero_documento }}</p>
                    <p><strong>Teléfono:</strong> {{ $responsable->user->telefono }}</p>
                    <p><strong>Email:</strong> {{ $responsable->user->email }}</p>
                    <p><strong>Cargo:</strong> {{ $responsable->user->cargo->nombre ?? 'N/A' }}</p>
                    <p><strong>Rol:</strong> {{ $responsable->user->rolPersonalizado->nombre ?? 'N/A' }}</p>
                    <p><strong>Sede:</strong> {{ $responsable->user->sede->nombre ?? 'N/A' }}</p>
                    <p><strong>Responsable desde:</strong> {{ $responsable->fecha_inicio }}</p>
                    <p><strong>Hasta:</strong> {{ $responsable->fecha_fin ?? 'Actual' }}</p>
                    <p><strong>Funciones:</strong> {{ $responsable->funciones }}</p>

                    {{-- Botón para mostrar documentos --}}
                    @if ($responsable->documentos)
                        <x-filament::button color="primary" onclick="window.dispatchEvent(new CustomEvent('open-documents'))">
                            Ver Documentos
                        </x-filament::button>
                    @endif
                </div>
            </div>

            {{-- Modal para documentos --}}
            @if ($responsable->documentos)
                <x-filament::modal id="documentos-modal" name="documentos-modal" :visible="false">
                    <x-slot name="heading">Documentos del Responsable</x-slot>

                    <ul class="list-disc ml-6">
                        @foreach (json_decode($responsable->documentos, true) as $documento)
                            <li>
                                <a href="{{ Storage::url($documento) }}" target="_blank" class="text-primary-600 hover:underline">
                                    {{ basename($documento) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </x-filament::modal>

                <script>
                    window.addEventListener('open-documents', () => {
                        document.querySelector('[name="documentos-modal"]').showModal();
                    });
                </script>
            @endif
        @else
            <p class="text-gray-500 text-sm">No hay un responsable del SST activo en este momento.</p>
        @endif
    </x-filament::card>
</x-filament::widget>
