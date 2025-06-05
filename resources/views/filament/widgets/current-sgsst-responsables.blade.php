{{-- resources/views/filament/widgets/current-sgsst-responsables.blade.php --}}
<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-semibold">Responsables SG-SST Actuales</h2>

        <ul class="mt-4 space-y-2">
            @forelse ($responsables as $r)
                <li>
                  
                    <br>
                    <small>Desde: {{ $r->fecha_inicio->format('Y-m-d') }}</small>
                </li>
            @empty
                <li class="text-gray-500">No hay responsables activos.</li>
            @endforelse
        </ul>
    </x-filament::card>
</x-filament::widget>
