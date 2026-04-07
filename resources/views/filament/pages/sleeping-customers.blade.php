<x-filament-panels::page>
    <div class="mb-4 max-w-xs">
        <label class="block text-sm font-medium mb-2">Sin visita desde</label>
        <select wire:model.live="days" class="fi-input block w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-700">
            @foreach ($this->getDaysOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    {{ $this->table }}
</x-filament-panels::page>
