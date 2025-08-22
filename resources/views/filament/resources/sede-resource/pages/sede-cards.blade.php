{{-- resources/views/filament/resources/sede-resource/pages/sede-cards.blade.php --}}
<x-filament::page>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($this->getRecords() as $sede)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border overflow-hidden">
                @if ($sede->foto)
                    <img
                        src="{{ Storage::url($sede->foto) }}"
                        alt="{{ $sede->nombre }}"
                        class="h-40 w-full object-cover"
                    >
                @endif

                <div class="p-4 space-y-1">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $sede->nombre }}
                    </h3>

                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Código: {{ $sede->codigo }}
                    </p>

                    @isset($sede->telefono)
                        <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <x-heroicon-o-phone class="w-4 h-4"></x-heroicon-o-phone>
                            {{ $sede->telefono }}
                        </p>
                    @endisset

                    @isset($sede->direccion)
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $sede->direccion }}
                        </p>
                    @endisset
                </div>

                <div class="px-4 pb-4 flex justify-between">
                    <x-filament::link
                        :url="route('filament.admin.resources.sedes.edit', $sede)"
                        tag="a"
                    >
                        Editar
                    </x-filament::link>

                    @if ($sede->google_maps_embed)
                        <x-filament::button
                            wire:click="$toggle('map_'.$sede->id)"
                            size="sm"
                            color="gray"
                        >
                            Mapa
                        </x-filament::button>
                    @endif
                </div>

                @if ($sede->google_maps_embed)
                    <div
                        x-show="$wire.get('map_{{ $sede->id }}')"
                        x-cloak
                        class="aspect-video"
                    >
                        {!! $sede->google_maps_embed !!}
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-filament::page>
