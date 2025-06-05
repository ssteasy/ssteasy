<x-filament::page>
    <form wire:submit.prevent="save" class="space-y-6 max-w-md mx-auto">
        {{ $this->form }}

        <x-filament::button type="submit" color="primary">
            Guardar cambios
        </x-filament::button>
    </form>
</x-filament::page>
