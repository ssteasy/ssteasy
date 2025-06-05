@if ($responsable)

    @if(auth()->user()->hasRole('colaborador'))
        {{-- —————————— VISTA PARA COLABORADOR —————————— --}}
        <h1 class="max-w-5xl mx-auto text-2xl font-bold text-gray-800 mb-6">
            Responsable SG SST
        </h1>
        <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-xl p-8 flex flex-col md:flex-row gap-10 items-start" style="padding:20px; align-items: center; border-radius: .75rem;">
            {{-- Foto, nombre, cargo y sede --}}
            <div class="flex-shrink-0 flex flex-col items-center">
                <img 
                  src="{{ $responsable->user->profile_photo_url }}" 
                  alt="Foto de {{ $responsable->user->primer_nombre }}" 
                  class="w-28 h-28 rounded-full object-cover border-4 border-indigo-500 shadow-md mb-4"
                />
                <h2 class="text-2xl font-extrabold text-gray-900 text-center">
                    {{ $responsable->user->primer_nombre }}
                    {{ $responsable->user->segundo_nombre }}
                    {{ $responsable->user->primer_apellido }}
                    {{ $responsable->user->segundo_apellido }}
                </h2>
                <p class="mt-1 text-indigo-600 font-semibold text-sm">
                    {{ $responsable->user->cargo->nombre ?? '-' }}
                </p>
                <p class="text-gray-500 text-sm mt-1 italic text-center">
                    Sede: {{ $responsable->user->sede->nombre ?? '-' }}
                </p>
            </div>

            {{-- Funciones + Contacto --}}
            <div class="flex-grow flex flex-col justify-between space-y-6">
                <div class="bg-indigo-50 rounded-xl p-5 shadow-inner">
                    <h3 class="text-lg font-bold text-indigo-700 mb-2">Responsabilidad SST</h3>
                    <p class="mt-3 text-gray-800 leading-relaxed whitespace-pre-line">
                        {{ $responsable->funciones }}
                    </p>
                </div>
                <div class="space-y-1">
                    <p class="text-gray-700"><span class="font-medium">Teléfono:</span> {{ $responsable->user->telefono }}</p>
                    <p class="text-gray-700"><span class="font-medium">Email:</span> {{ $responsable->user->email }}</p>
                </div>
            </div>
        </div>

    @else
        {{-- —————————— VISTA COMPLETA PARA ADMIN/SUPERADMIN —————————— --}}
        <h1 class="max-w-5xl mx-auto text-2xl font-bold text-gray-800 mb-6">
            Responsable SG SST
        </h1>
        <div class="max-w-5xl mx-auto bg-white rounded-3xl p-8 flex flex-col md:flex-row gap-10 items-start" style="padding:20px; align-items: center; border-radius: .75rem;">
          <!-- Foto y datos personales -->
          <div class="flex-shrink-0 flex flex-col items-center ">
            <img 
              src="{{ $responsable->user->profile_photo_url }}" 
              alt="Foto de {{ $responsable->user->primer_nombre }}" 
              class="w-28 h-28 rounded-full object-cover border-4 border-indigo-500 shadow-md mb-6"
            />
            <div class="text-center md:text-left">
              <h2 class="text-2xl font-extrabold text-gray-900 leading-tight">
                {{ $responsable->user->primer_nombre }} {{ $responsable->user->segundo_nombre }}
                {{ $responsable->user->primer_apellido }} {{ $responsable->user->segundo_apellido }}
              </h2>
              <p class="mt-1 text-indigo-600 font-semibold text-sm">
                {{ $responsable->user->cargo->nombre ?? '-' }}  {{ $responsable->user->rolPersonalizado->nombre ?? '' }}
              </p>
              <p class="text-gray-500 text-sm mt-1 italic">
                Sede: {{ $responsable->user->sede->nombre ?? '-' }}
              </p>
            </div>
          </div>

          <!-- Info detallada y responsabilidades -->
          <div class="flex-grow flex flex-col justify-between space-y-6">
            <div>
              <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-gray-700">
                <div>
                  <dt class="font-medium text-sm text-gray-500">Documento</dt>
                  <dd class="mt-1 text-base font-semibold">
                    {{-- abreviar “cedula de ciudadania” a “c.c.” --}}
                    {{ $responsable->user->tipo_documento === 'Cédula de ciudadanía' ? 'C.C.' : $responsable->user->tipo_documento }}: 
                    {{ $responsable->user->numero_documento }}
                  </dd>
                </div>
                <div>
                  <dt class="font-medium text-sm text-gray-500">Teléfono</dt>
                  <dd class="mt-1 text-base font-semibold">{{ $responsable->user->telefono }}</dd>
                </div>
                <div>
                  <dt class="font-medium text-sm text-gray-500">Email</dt>
                  <dd class="mt-1 text-base font-semibold">{{ $responsable->user->email }}</dd>
                </div>
                <div>
                  <dt class="font-medium text-sm text-gray-500">Tipo</dt>
                  <dd class="mt-1 text-base font-semibold">{{ $responsable->user->tipo_documento }}</dd>
                </div>
              </dl>
            </div>

            <div class="bg-indigo-50 rounded-xl p-5 shadow-inner">
              <h3 class="text-lg font-bold text-indigo-700 mb-2">Responsabilidad SST</h3>
              <p class="text-indigo-900 font-semibold"><span class="font-medium">Inicio:</span> {{ $responsable->fecha_inicio->format('Y-m-d') }}</p>
              @if($responsable->fecha_fin)
                <p class="text-indigo-700 text-sm"><span class="font-medium">Fin:</span> {{ $responsable->fecha_fin->format('Y-m-d') }}</p>
              @endif
              <p class="mt-3 text-gray-800 leading-relaxed whitespace-pre-line">{{ $responsable->funciones }}</p>
            </div>

            @if ($responsable->documentos)
                {{-- sección de documentos (solo para Admin/Superadmin) --}}
                <div x-data="{ open: false, fileUrl: '', fileTitle: '' }" @keydown.escape.window="open = false" class="mt-4">
                    <h4 class="text-sm font-semibold mb-1">Documentos:</h4>
                    <ul class="list-disc list-inside text-sm text-blue-600">
                        @foreach ($responsable->documentos as $doc)
                            <li>
                                <button 
                                    @click="fileUrl = '{{ Storage::url($doc['file']) }}'; fileTitle = '{{ $doc['titulo'] ?? 'Documento' }}'; open = true"
                                    class="underline hover:text-blue-800 text-left"
                                >
                                    {{ $doc['titulo'] ?? 'Documento' }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    {{-- modal con animaciones… (idéntico al que tenías) --}}
                    <!-- ...el mismo código de tu modal aquí... -->
                </div>
            @endif
          </div>
        </div>
    @endif

@else
    <div class="max-w-3xl mx-auto p-8 text-center text-yellow-800 font-semibold">
        ⚠️ No hay ningún responsable activo en este momento.
    </div>
@endif

{{-- —————————— Breadcrumbs y acciones (idéntico a lo que tenías) —————————— --}}
@if(auth()->user()->hasRole('admin'))
    <!-- tu bloque de <header class="fi-header">… </header> -->
@endif
@if(auth()->user()->hasRole('colaborador'))
    <style>
        div[x-load-src="https://app.ssteasy.com/js/filament/tables/components/table.js?v=3.3.10.0"] {
          display: none !important;
        }
    </style>
@endif
