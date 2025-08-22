{{-- resources/views/filament/pages/profile.blade.php --}}
<x-filament::page>
    <div class="flex justify-center">
        <div
            class="relative w-full max-w-3xl bg-white rounded-xl shadow-lg ring-1 ring-gray-200
                   dark:bg-gray-900 dark:ring-gray-700 overflow-hidden">

            {{-- Barra superior de acento corporativo --}}
            <div class="h-2 bg-primary-600"></div>

            {{-- CONTENIDO TIPO CARNET EN 2 COLUMNAS ----------------------------------}}
            <div class="grid grid-cols-1 md:grid-cols-2">
                {{-- COLUMNA IZQUIERDA --------------------------------------------------}}
                <div class="p-6 flex flex-col items-center md:items-start border-b md:border-b-0 md:border-r
                            border-gray-100 dark:border-gray-800">

                    {{-- Foto + nombre + cargo --}}
                    <img
                        src="{{ $this->user->profile_photo_url ?: 'https://app.ssteasy.com/images/pp.png' }}"
                        alt="Foto de perfil"
                        class="h-36 w-36 rounded-full object-cover shadow-md ring-4 ring-primary-500"
                    >

                    <h2 class="mt-4 text-xl font-semibold tracking-wide text-gray-800 dark:text-gray-100 text-center md:text-left">
                        {{ collect([$this->user->primer_nombre, $this->user->segundo_nombre, $this->user->primer_apellido, $this->user->segundo_apellido])->filter()->join(' ') }}
                    </h2>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ optional($this->user->cargo)->nombre ?? '—' }}
                    </p>

                    {{-- Datos personales breves --}}
                    <dl class="mt-6 w-full text-sm text-gray-700 dark:text-gray-300 space-y-1">
                        <div class="flex justify-between">
                            <dt class="font-medium">Documento:</dt>
                            <dd>{{ $this->user->tipo_documento }} — {{ $this->user->numero_documento }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="font-medium">Sexo:</dt>
                            <dd>{{ $this->user->sexo }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="font-medium">Teléfono:</dt>
                            <dd>{{ $this->user->telefono ?: '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="font-medium">Correo:</dt>
                            <dd class="text-right">{{ $this->user->email }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- COLUMNA DERECHA --------------------------------------------------}}
                <div class="p-6 flex flex-col gap-6">
                    {{-- Información de contrato --}}
                    <div>
                        <h3 class="text-sm font-semibold uppercase text-primary-600 mb-2">Contrato</h3>
                        <dl class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                            <div class="flex justify-between">
                                <dt class="font-medium">Tipo:</dt>
                                <dd>{{ $this->user->tipo_contrato }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium">Modalidad:</dt>
                                <dd>{{ $this->user->modalidad }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium">Riesgo:</dt>
                                <dd>{{ $this->user->nivel_riesgo }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium">Inicio:</dt>
                                <dd>{{ $this->user->fecha_inicio?->format('d-m-Y') ?: '—' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium">Fin:</dt>
                                <dd>{{ $this->user->fecha_fin?->format('d-m-Y') ?: '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Seguridad social --}}
                    <div>
                        <h3 class="text-sm font-semibold uppercase text-primary-600 mb-2">Seguridad Social</h3>
                        <dl class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                            <div class="flex justify-between">
                                <dt class="font-medium">EPS:</dt>
                                <dd>{{ $this->user->eps }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium">IPS:</dt>
                                <dd>{{ $this->user->ips ?: '—' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium">ARL:</dt>
                                <dd>{{ $this->user->arl }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium">AFP:</dt>
                                <dd>{{ $this->user->afp }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Ubicación --}}
                    <div>
                        <h3 class="text-sm font-semibold uppercase text-primary-600 mb-2">Ubicación</h3>
                        <dl class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                            <div class="flex justify-between">
                                <dt class="font-medium">Dirección:</dt>
                                <dd class="text-right">{{ $this->user->direccion ?: '—' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium">DANE:</dt>
                                <dd class="text-right">
                                    {{ optional($this->user->pais)->nombre         ?? '—' }} /
                                    {{ optional($this->user->departamento)->nombre ?? '—' }} /
                                    {{ optional($this->user->municipio)->nombre    ?? '—' }}
                                    — Zona {{ $this->user->zona }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Banner de edición (a lo ancho de las 2 columnas) --}}
                <div
                    class="md:col-span-2 mx-6 mb-6 rounded-lg bg-yellow-50 border-l-4 border-yellow-400
                           px-4 py-3 text-xs text-yellow-800 shadow-sm
                           dark:border-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-200">
                    Si necesitas actualizar algún dato, solicita el cambio a un <span class="font-semibold">administrador</span>.
                </div>
            </div>
        </div>
    </div>

    {{-- PIE DE PÁGINA --}}
    <p class="mt-6 text-xs text-center text-gray-400">
        Última actualización: {{ $this->user->updated_at->format('d-m-Y H:i') }}
    </p>
</x-filament::page>
