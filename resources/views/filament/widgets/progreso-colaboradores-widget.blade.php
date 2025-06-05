<x-filament::widget>
    <x-filament::card>
        {{-- Encabezado --}}
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Progreso de Colaboradores</h2>
            <p class="text-sm text-gray-500">{{ $this->cantidadColaboradores }} colaboradores</p>
        </div>

        {{-- Cursos activos totales --}}
        <div class="mt-2">
            <p class="text-sm text-gray-600">
                Cursos activos en la empresa: 
                <span class="font-medium">{{ $this->cantidadCursosActivos }}</span>
            </p>
        </div>

        {{-- Progreso promedio de la empresa --}}
        <div class="mt-4">
            <div class="flex items-center space-x-2">
                <span class="font-semibold">Avance general:</span>
                <span class="text-primary-600">{{ $this->promedioEmpresa }}%</span>
            </div>
            <div class="w-full h-2 bg-gray-200 rounded-full mt-2">
                <div
                    class="h-full bg-primary-600"
                    style="width: {{ $this->promedioEmpresa }}%;"
                ></div>
            </div>
        </div>

        {{-- Tres colaboradores con menor avance --}}
        @if($this->tresConMenorAvance->isNotEmpty())
            <div class="mt-6">
                <h3 class="font-medium">Atención Requerida</h3>
                <ul class="mt-2 space-y-3">
                    @foreach($this->tresConMenorAvance as $colab)
                        <li class="flex items-center justify-between p-3 border rounded-lg">
                            <div>
                                <p class="font-semibold">{{ $colab['nombre'] }}</p>
                                <p class="text-xs text-gray-500">
                                    Cursos inscritos: {{ $colab['cursos'] }}
                                </p>
                            </div>
                            <span class="text-sm font-medium text-primary-600">
                                {{ $colab['porcentaje'] }}%
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <p class="mt-6 text-gray-500 text-sm">
                No hay colaboradores con progreso bajo.
            </p>
        @endif
    </x-filament::card>
</x-filament::widget>
