<x-filament::page>
    <x-filament-panels::form wire:submit="vote" class="space-y-6">
        {{ $this->form }}

        <x-filament::button type="submit">
            Votar
        </x-filament::button>
    </x-filament-panels::form>
</x-filament::page>
