<x-filament::page>
    @php /** @var \App\Models\Sede $record */ $record = $this->record; @endphp

    <x-filament::card class="p-0">
        <div class="flex flex-col lg:flex-row" style="overflow: hidden;">   {{-- layout responsive --}}

            {{-- FOTO (máx. 1/3) --}}
            <div class="lg:w-1/3 flex-shrink-0">
                @if ($record->foto)
                    <img src="{{ Storage::url($record->foto) }}"
                         alt="{{ $record->nombre }}"
                         class="h-56 lg:h-full w-full object-cover">
                @else
                    <div
                        class="h-56 lg:h-full w-full bg-gray-200 dark:bg-gray-700
                               flex items-center justify-center">
                        <x-heroicon-o-photo class="w-10 h-10 text-gray-400"/>
                    </div>
                @endif
            </div>

            {{-- INFORMACIÓN + MAPA (2/3) --}}
            <div class="flex-1 p-6 space-y-6">
                {{-- Datos de la sede --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <h2 class="text-2xl font-bold">{{ $record->nombre }}</h2>
                        @unless ($record->activo)
                            <span
                                class="text-xs font-semibold bg-red-100 text-red-700 px-2 py-0.5 rounded">
                                Inactiva
                            </span>
                        @endunless
                    </div>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2
                               text-sm text-gray-700 dark:text-gray-300">
                        <div><dt class="font-medium">Código</dt><dd>{{ $record->codigo }}</dd></div>
                        <div><dt class="font-medium">NIT</dt><dd>{{ $record->nit ?? '—' }}</dd></div>
                        <div class="sm:col-span-2">
                            <dt class="font-medium">Actividad económica</dt>
                            <dd>{{ $record->actividad_economica ?? '—' }}</dd>
                        </div>
                        <div><dt class="font-medium">Teléfono</dt><dd>{{ $record->telefono ?? '—' }}</dd></div>
                        <div><dt class="font-medium">Contacto</dt><dd>{{ $record->persona_contacto ?? '—' }}</dd></div>
                        <div class="sm:col-span-2 flex items-start gap-1">
                            <dt class="font-medium shrink-0 mt-0.5">Dirección</dt>
                            <dd>{{ $record->direccion ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Google Maps (solo si hay iframe) --}}
                @if ($record->google_maps_embed)
                    <div class="aspect-video w-full">
                        {!! $record->google_maps_embed !!}
                    </div>
                @endif
            </div>
        </div>
    </x-filament::card>
</x-filament::page>
