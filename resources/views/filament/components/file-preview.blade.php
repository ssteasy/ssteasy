<div style="height:85vh;" x-data>
    {{-- Barra superior --}}
    <div class="flex justify-between items-center bg-gray-100 px-4 py-2">
        <h2 class="text-lg font-semibold">Visor de archivos</h2>

        <div class="space-x-2">
            {{-- Descargar --}}
            <a href="{{ Storage::url($record->file_path) }}"
               download
               class="filament-button filament-button-size-sm filament-button-color-gray">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-1" />
                Descargar
            </a>


        </div>
    </div>

    {{-- Visor --}}
    <iframe style="height:80vh;" src="{{ Storage::url($record->file_path) }}#toolbar=0"
            class="w-full h-[80vh]"></iframe>
</div>
