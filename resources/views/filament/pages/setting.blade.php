<x-filament-panels::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}
        <br><br>
        <div class="mt-4">
            <x-filament::button type="submit">
                Save Settings
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
