@php use Illuminate\Support\Str; @endphp

<x-filament::page>
    {{-- —————— Cabecera del curso —————— --}}
    <div class="space-y-2">
        <h1 class="text-3xl font-bold">
            {{ $record->nombre_capacitacion }}
        </h1>

        <div class="prose max-w-full">
            {!! $record->objetivo !!}
        </div>
    </div>
    {{-- Botón de certificado --}}
    <div class="mt-4">
        @if($this->yaTieneCertificado)
            <x-filament::button wire:click="descargarCertificado" color="primary" wire:loading.attr="disabled"
                wire:target="descargarCertificado">
                Descargar Certificado
            </x-filament::button>
        @elseif($this->puedeCertificar)
            <x-filament::button wire:click="descargarCertificado" color="success" wire:loading.attr="disabled"
                wire:target="descargarCertificado">
                Obtener Certificado
            </x-filament::button>
        @endif
    </div>

    {{-- —————— Bloque de la sesión activa —————— --}}
    @if($currentSession)
        <x-filament::card class="mt-6">
            {{-- Título de la sesión --}}
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">{{ $currentSession->titulo }}</h3>

                {{-- Si no tiene preguntas, botón “Terminar sesión” --}}
                @if(empty($currentSession->preguntas))
                    <x-filament::button wire:click="terminateSession" color="secondary" wire:loading.attr="disabled"
                        wire:target="terminateSession">
                        Terminar sesión
                    </x-filament::button>
                @endif
            </div>

            {{-- Contenido HTML --}}
            <div class="prose max-w-full">
                {!! $currentSession->contenido_html !!}
            </div>

            {{-- Video embebido --}}
            @php
                $url = $currentSession->video_url;
                $embedUrl = null;

                if (Str::contains($url, 'youtube.com') || Str::contains($url, 'youtu.be')) {
                    if (preg_match('/youtu\.be\/([^\?]+)/', $url, $m)) {
                        $videoId = $m[1];
                    } elseif (preg_match('/v=([^&]+)/', $url, $m)) {
                        $videoId = $m[1];
                    }

                    if (!empty($videoId)) {
                        $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                    }
                }
            @endphp

            @if($embedUrl)
                <div class="mt-4 aspect-video">
                    <iframe src="{{ $embedUrl }}" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen class="w-full h-full rounded" style="height: 50vh;"></iframe>
                </div>
            @elseif($currentSession->video_url)
                <p class="text-sm text-red-600">
                    La URL de vídeo no es válida para embeber. Usa formato “embed”.
                </p>
            @endif

            {{-- Formulario de preguntas --}}
            @if(!empty($currentSession->preguntas))
                @php
                    // Obtenemos el pivote directamente desde el método del componente
                    $pivot = $this->getPivotFor($currentSession->id);
                    $completada = $pivot?->completado_at !== null;
                @endphp

                @if($completada)
                    {{-- Ya completó la sesión: solo mostramos el mensaje --}}
                    <p class="mt-4 font-semibold text-green-600">
                        Ya completaste esta sesión. Tu nota: {{ $pivot->score ?? 'N/A' }}%
                    </p>
                    {{-- Botón para “volver” al listado de sesiones --}}
                    <x-filament::button class="mt-4" wire:click="$set('currentSession', null)">
                        Volver a Sesiones
                    </x-filament::button>
                @else
                    <form wire:submit.prevent="submitAnswers" class="mt-6 space-y-6">
                        @foreach($currentSession->preguntas as $i => $p)
                            <div class="border rounded-lg p-4">
                                <p class="font-medium">
                                    {{ $loop->iteration }}. {{ $p['enunciado'] }}
                                </p>

                                @if($p['tipo'] === 'vf')
                                    <div class="mt-2 flex space-x-6">
                                        <label class="inline-flex items-center">
                                            <input type="radio" wire:model="answers.{{ $i }}" value="1" class="mr-2" /> Verdadero
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" wire:model="answers.{{ $i }}" value="0" class="mr-2" /> Falso
                                        </label>
                                    </div>
                                @elseif($p['tipo'] === 'unica')
                                    <div class="mt-2 space-y-2">
                                        @foreach($p['opciones'] as $j => $opt)
                                            <label class="block">
                                                <input type="radio" wire:model="answers.{{ $i }}" value="{{ $j }}" class="mr-2" />
                                                {{ $opt['texto'] }}
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <x-filament::button type="submit" color="primary" wire:loading.attr="disabled" wire:target="submitAnswers">
                            Enviar respuestas
                        </x-filament::button>
                    </form>

                    @if($score !== null)
                        <p class="mt-4 font-semibold">Tu nota: {{ $score }}%</p>
                    @endif
                @endif
            @endif
        </x-filament::card>
    @endif

    {{-- —————— Listado de Sesiones —————— --}}
    <x-filament::card class="mt-6">
        <h3 class="text-lg font-medium mb-4">Sesiones</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($record->sesiones as $sesion)
                @php
                    // Idéntica lógica: usamos el método getPivotFor para cada sesión
                    $pivot = $this->getPivotFor($sesion->id);
                    $completada = $pivot?->completado_at !== null;
                @endphp

                <div class="flex flex-col h-full border rounded-lg shadow-sm overflow-hidden">
                    @if($sesion->miniatura)
                        <img src="{{ Storage::disk('public')->url($sesion->miniatura) }}" alt="{{ $sesion->titulo }}"
                            class="h-32 w-full object-cover" />
                    @endif

                    <div class="p-4 flex-1 flex flex-col">
                        <h4 class="font-semibold text-lg">
                            {{ $sesion->orden }}. {{ $sesion->titulo }}
                        </h4>
                        <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                            {!! Str::limit(strip_tags($sesion->contenido_html), 100) !!}
                        </p>

                        <div class="mt-auto pt-4 flex justify-end">
                            @if($completada)
                                {{-- Ya completada: mostramos la nota --}}
                                <span class="text-green-600 font-semibold">
                                    Completada
                                    @if($pivot->score !== null)
                                        ({{ $pivot->score }}%)
                                    @endif
                                </span>
                            @else
                                {{-- Aún no completada: botón para ver/contestar --}}
                                <x-filament::button wire:click="viewSession({{ $sesion->id }})" size="sm"
                                    wire:loading.attr="disabled" wire:target="viewSession({{ $sesion->id }})">
                                    @if(empty($sesion->preguntas))
                                        Terminar
                                    @else
                                        Responder
                                    @endif
                                </x-filament::button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No hay sesiones disponibles.</p>
            @endforelse
        </div>
    </x-filament::card>
</x-filament::page>