<div class="px-4 sm:px-6 lg:px-8">

    {{-- <x-radio-group class="hidden sm:grid grid-cols-4 gap-2" wire:model.live="filters.island">
        @foreach ($filters->islands() as $island)
            <x-radio-group.option
                class="px-3 py-2 flex flex-col rounded-xl border hover:border-blue-400 text-gray-700 cursor-pointer"
                class-checked="text-blue-600 border-2 border-blue-400"
                class-not-checked="text-gray-700"
                :value="$island['label']"
                >
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500"> {{ $island['label'] }} </dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900"> {{ $island['count'] }} </dd>
                </div>
            </x-radio-group.option>
        @endforeach
    </x-radio-group> --}}

    <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
        @foreach ($filters->islands() as $island)
            <button wire:click="$set('filters.island', '{{$island['value']}}')" class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500"> {{ $island['label'] }} </dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900"> {{ $island['count'] }} </dd>
            </button>
        @endforeach
    </dl>
    <livewire:report.chart :$query :filters=$filters lazy />
    <livewire:report.table :$query :filters=$filters lazy />
</div>
