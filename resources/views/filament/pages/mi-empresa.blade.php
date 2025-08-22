<x-filament::page>
    {{-- ===== ENCABEZADO EMPRESA ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        {{-- Información principal --}}
        <x-filament::card>
            <div class="flex items-center space-x-4">
                @if ($empresa->logo)
                    <img src="{{ Storage::url($empresa->logo) }}"
                         alt="Logo {{ $empresa->nombre }}"
                         class="h-16 w-16 rounded-full object-cover"/>
                @endif

                <div>
                    <h2 class="text-2xl font-bold">
                        {{ $empresa->nombre }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        {{ $empresa->razon_social }}
                    </p>
                </div>
            </div>
        </x-filament::card>

        {{-- Datos rápidos --}}
        <x-filament::card>
            <dl class="grid grid-cols-2 gap-x-2 gap-y-1 text-sm">
                <dt class="font-medium">NIT</dt>
                <dd>{{ $empresa->nit }}</dd>

                <dt class="font-medium">Teléfono</dt>
                <dd>{{ $empresa->telefono }}</dd>

                <dt class="font-medium">Email</dt>
                <dd>{{ $empresa->email }}</dd>

                <dt class="font-medium">Ciudad</dt>
                <dd>{{ $empresa->ciudad }}</dd>

                <dt class="font-medium">Sitio Web</dt>
                <dd>
                    @if ($empresa->website)
                        <a href="{{ $empresa->website }}" target="_blank" class="text-primary-600 underline">
                            {{ \Illuminate\Support\Str::limit($empresa->website, 25) }}
                        </a>
                    @else
                        —
                    @endif
                </dd>
            </dl>
        </x-filament::card>

        {{-- KPI empleados --}}
        <x-filament::card>
            <div class="flex flex-col justify-center items-center h-full">
                <div class="text-sm text-gray-500">Empleados activos</div>
                <div class="text-3xl font-bold">{{ $empleadosCount }}</div>
            </div>
        </x-filament::card>
    </div>

    {{-- ===== TABLA EMPLEADOS ===== --}}
    <x-filament::section>
        <x-slot name="header">Empleados</x-slot>
        <x-slot name="description">Listado y búsqueda</x-slot>

        <livewire:empresa-empleados-table :empresa="$empresa" />
    </x-filament::section>
</x-filament::page>
