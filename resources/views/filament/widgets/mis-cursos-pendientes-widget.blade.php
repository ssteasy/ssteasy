<x-filament::widget>
    <x-filament::card>
        {{-- Título y cantidad --}}
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Mis Cursos Pendientes</h2>
            <p class="text-sm text-gray-500">{{ $this->cantidadPendientes }} en progreso</p>
        </div>

        {{-- Progreso promedio --}}
        <div class="mt-4">
            <div class="flex items-center space-x-2">
                <span class="font-semibold">Progreso promedio:</span>
                <span class="text-primary-600">{{ $this->promedioGlobal }}%</span>
            </div>
            <div class="w-full h-2 bg-gray-200 rounded-full mt-2">
                <div
                    class="h-full bg-primary-600"
                    style="width: {{ $this->promedioGlobal }}%;"
                ></div>
            </div>
        </div>

        {{-- Tres cursos con menor avance --}}
        @if($this->tresConMenorAvance->isNotEmpty())
            <div class="mt-6">
                <h3 class="font-medium">Próximos por avanzar</h3>
                <ul class="mt-2 space-y-3">
                    @foreach($this->tresConMenorAvance as $curso)
                        <li class="flex items-center justify-between p-3 border rounded-lg">
                            <div>
                                <p class="font-semibold">{{ $curso['nombre'] }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $curso['aprobadas'] }} / {{ $curso['totalSesiones'] }} sesiones
                                </p>
                            </div>
                            <span class="text-sm font-medium text-primary-600">
                                {{ $curso['progreso'] }}%
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <p class="mt-6 text-gray-500 text-sm">No tienes cursos pendientes.</p>
        @endif
    </x-filament::card>
</x-filament::widget>
