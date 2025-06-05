<x-filament::page>
    <div class="space-y-4">
        <div>
            <h1 class="text-2xl font-bold">Consola Web Tinker (DESHABILITADA POR SEGURIDAD)</h1>
            <p>La consola está deshabilitada temporalmente. por seguridad....</p>
        </div>

        <!-- Selección de presets -->
        <div class="flex items-center space-x-2">
            <label for="preset" class="font-medium">Presets:</label>
            <select id="preset" class="border rounded p-2" onchange="document.getElementById('tinker-iframe').contentWindow.document.querySelector('textarea').value = this.value;" disabled>
                <option value="">-- Selecciona un preset --</option>
                @foreach ($presets as $label => $code)
                    <option value="{{ $code }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <!-- Consola deshabilitada -->
        <div class="relative">
            <iframe
                id="tinker-iframe"
                src="{{ URL::to(config('web-tinker.path')) }}"
                class="w-full h-[600px] border rounded pointer-events-none select-none"
                tabindex="-1"
            ></iframe>
            <div class="absolute inset-0 bg-transparent cursor-not-allowed"></div>
        </div>
    </div>
</x-filament::page>