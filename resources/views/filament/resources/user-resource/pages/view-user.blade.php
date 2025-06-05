{{-- resources/views/filament/resources/user-resource/pages/view-user.blade.php --}}
<x-filament::page>
     <style>
        /* Estilos de impresión */
        @media print {
            /* Oculta todo… */
            body * { visibility: hidden !important; }
            header { display: none  !important; }
            /* … excepto el contenedor resume-container */
            .hv { position:absolute; }
            .hv * { visibility: visible !important; }
            .resume-container { 
                position: absolute; 
                top: 0; left: 0; 
                width: 100%; 
            }
        }
        /* Asegúrate de que tu resume se vea bien en pantalla */
        p, h1, h2, h3, h4 { color: #000 !important; }
    </style>
    <div class="hv flex bg-white shadow-lg rounded-lg overflow-hidden">
        {{-- Panel izquierdo --}}
        <aside class="w-1/3 bg-primary text-white p-6 flex flex-col items-center">
            @if($this->user->profile_photo_path)
                <img src="{{ $this->user->profile_photo_url }}"
                     alt="Avatar"
                     class="w-32 h-32 rounded-full object-cover mb-4 border-4 border-white">
            @else
                <div class="w-32 h-32 rounded-full bg-secondary flex items-center justify-center text-2xl font-bold mb-4">
                    {{ strtoupper(substr($this->user->primer_nombre,0,1)) }}
                </div>
            @endif

            <h2 class="text-2xl font-bold mb-1 text-center">
                {{ $this->user->primer_nombre }} {{ $this->user->segundo_nombre }}
            </h2>
            <p class="uppercase tracking-wide mb-4 text-center">
                {{ $this->user->primer_apellido }} {{ $this->user->segundo_apellido }}
            </p>

            <div class="w-full border-t border-white my-4"></div>
            <div class="w-full space-y-2 text-sm">
                <p><span class="font-semibold">Email:</span><br>{{ $this->user->email }}</p>
                <p><span class="font-semibold">Teléfono:</span><br>{{ $this->user->telefono }}</p>
                <p><span class="font-semibold">Dirección:</span><br>{{ $this->user->direccion }}</p>
                <p><span class="font-semibold">Zona:</span> {{ $this->user->zona }}</p>
            </div>

            {{-- Empresa + Logo --}}
            <div class="mt-auto w-full border-t border-white pt-4 text-sm text-center">
                <p class="font-semibold mb-1">Empresa</p>
                <p>{{ $this->user->empresa?->nombre }}</p>

                @if($this->user->empresa?->logo)
                    <img src="{{ \Storage::url($this->user->empresa->logo) }}"
                         alt="Logo de {{ $this->user->empresa->nombre }}"
                         class="mx-auto mt-2 w-16 h-16 object-contain bg-white p-1 rounded">
                @endif
            </div>
        </aside>

        {{-- Panel derecho --}}
        <main class="w-2/3 p-6 space-y-6">
            {{-- Encabezado --}}
            <div class="text-center">
                <h1 class="text-3xl font-bold mb-1">Hoja de Vida</h1>
                <p class="text-lg text-gray-600">
                    {{ $this->user->primer_nombre }}
                    {{ $this->user->segundo_nombre }}
                    {{ $this->user->primer_apellido }}
                    {{ $this->user->segundo_apellido }}
                </p>
            </div>

            {{-- Perfil --}}
            <section>
                <h3 class="text-xl font-semibold border-b-2 border-secondary inline-block mb-2">Perfil</h3>
                <p class="text-gray-700">
                    Usuario con rol(s): {{ $this->user->roles->pluck('name')->implode(', ') }}.<br>
                    Contrato {{ $this->user->tipo_contrato }} desde {{ optional($this->user->fecha_inicio)->format('Y-m-d') }}.
                </p>
            </section>

            {{-- Detalles personales --}}
            <section class="grid grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold uppercase text-sm text-gray-600 mb-1">Documento</h4>
                    <p>{{ $this->user->tipo_documento }}: {{ $this->user->numero_documento }}</p>
                </div>
                <div>
                    <h4 class="font-semibold uppercase text-sm text-gray-600 mb-1">Contrato & Sede</h4>
                    <p>{{ $this->user->tipo_contrato }} / {{ $this->user->modalidad }}</p>
                    <p>Sede: {{ $this->user->sede?->nombre }}</p>
                </div>
            </section>

            {{-- Ubicación --}}
            <section class="grid grid-cols-3 gap-6">
                <div>
                    <h4 class="font-semibold uppercase text-sm text-gray-600 mb-1">País</h4>
                    <p class="font-medium">
                        {{ $this->user->pais_dane }}
                        @if($this->user->pais?->nombre) — {{ $this->user->pais->nombre }} @endif
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold uppercase text-sm text-gray-600 mb-1">Departamento</h4>
                    <p class="font-medium">
                        {{ $this->user->departamento_dane }}
                        @if($this->user->departamento?->nombre) — {{ $this->user->departamento->nombre }} @endif
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold uppercase text-sm text-gray-600 mb-1">Municipio</h4>
                    <p class="font-medium">
                        {{ $this->user->municipio_dane }}
                        @if($this->user->municipio?->nombre) — {{ $this->user->municipio->nombre }} @endif
                    </p>
                </div>
            </section>

            {{-- Seguridad social --}}
            <section class="grid grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold uppercase text-sm text-gray-600 mb-1">Seguridad Social</h4>
                    <p>EPS: {{ $this->user->eps }}</p>
                    <p>ARL: {{ $this->user->arl }}</p>
                    <p>AFP: {{ $this->user->afp }}</p>
                    <p>IPS: {{ $this->user->ips ?? '—' }}</p>
                </div>
                <div>
                    <h4 class="font-semibold uppercase text-sm text-gray-600 mb-1">Información Adicional</h4>
                    <p>Sexo: {{ $this->user->sexo }}</p>
                    <p>Nivel de Riesgo: {{ $this->user->nivel_riesgo }}</p>
                </div>
            </section>
        </main>
    </div>
</x-filament::page>