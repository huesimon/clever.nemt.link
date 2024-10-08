@section('title', $user ? 'Favorites - clever.nemt.link' : 'Locations - clever.nemt.link')
<div class="" style="">
    <div class="bg-gray-100">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="pb-6">
                <div class="flex flex-row space-x-4 mb-4">
                    <div class="w-2/3">
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <div class="mt-1">
                            <input wire:model.live='search' type="search" name="search" id="search"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Search... Advanced filters (city: Copenhagen, or zip: 2100)">
                        </div>
                    </div>


                </div>
                <div class="flex flex-col md:flex-row md:space-x-4">
                    <div class="mt-1 w-full md:w-1/3">
                        <label for="kwh" class="block text-sm font-medium text-gray-700">Speed</label>
                        <x-dropdown-menu id="1" :selectText="$kwh?->label()">
                            <x-dropdown-item :active="!$kwh" wire:click="$set('kwh', null)">
                                All
                            </x-dropdown-item>
                            @foreach (\App\Enums\ChargeSpeed::cases() as $speed)
                            {{-- @dump($speed, $kwh) --}}
                            <x-dropdown-item :active="$speed === $kwh" wire:click="$set('kwh', '{{ $speed }}')">
                                {{ $speed->label() }}
                            </x-dropdown-item>
                            @endforeach
                        </x-dropdown-menu>
                    </div>
                    <div class="mt-1 w-full md:w-1/3">
                        <label for="kwh" class="block text-sm font-medium text-gray-700">Parking Type</label>
                        <x-dropdown-menu id="2"  :selectText="$parkingType?->label()">
                            <x-dropdown-item
                                :active="!$parkingType"
                                wire:click="$set('parkingType', null)"
                            >
                                All
                            </x-dropdown-item>
                            @foreach (\App\Enums\ParkingTypes::cases() as $type)
                            <x-dropdown-item wire:click="$set('parkingType', '{{ $type }}')" :active="$type === $parkingType">
                                {{ $type->label() }}
                            </x-dropdown-item>
                            @endforeach
                        </x-dropdown-menu>
                    </div>
                    <!-- Toggle -->
                    <div
                        x-data="{ value: $wire.get('onlyClever') }"
                        class="flex items-center"
                        x-id="['toggle-label']"
                        >
                        <input type="hidden" name="sendNotifications" :value="value">

                        <!-- Label -->
                        <label
                            @click="$refs.toggle.click(); $refs.toggle.focus()"
                            :id="$id('toggle-label')"
                            class="text-gray-900 font-medium"
                        >
                            Only Clever
                        </label>

                        <!-- Button -->
                        <button
                            x-ref="toggle"
                            @click="value = ! value"
                            type="button"
                            role="switch"
                            wire:click="$toggle('onlyClever')"
                            :aria-checked="value"
                            :aria-labelledby="$id('toggle-label')"
                            :class="value ? 'bg-slate-400' : 'bg-slate-300'"
                            class="relative ml-4 inline-flex w-14 rounded-full py-1 transition"
                        >
                            <span
                                :class="value ? 'translate-x-7' : 'translate-x-1'"
                                class="bg-white h-6 w-6 rounded-full transition shadow-md"
                                aria-hidden="true"
                            ></span>
                        </button>
                    </div>
                    {{-- Show InProximity --}}
                    <div
                    x-data="{ value: $wire.get('showInProximity') }"
                    class="flex items-center"
                    x-id="['toggle-label']"
                    >
                        <input type="hidden" name="sendNotifications" :value="value">

                        <!-- Label -->
                        <label
                            @click="$refs.toggle.click(); $refs.toggle.focus()"
                            :id="$id('toggle-label')"
                            class="text-gray-900 font-medium"
                        >
                            Show InProximity
                        </label>

                        <!-- Button -->
                        <button
                            x-ref="toggle"
                            @click="value = ! value"
                            type="button"
                            role="switch"
                            wire:click="$toggle('showInProximity')"
                            :aria-checked="value"
                            :aria-labelledby="$id('toggle-label')"
                            :class="value ? 'bg-slate-400' : 'bg-slate-300'"
                            class="relative ml-4 inline-flex w-14 rounded-full py-1 transition"
                        >
                            <span
                                :class="value ? 'translate-x-7' : 'translate-x-1'"
                                class="bg-white h-6 w-6 rounded-full transition shadow-md"
                                aria-hidden="true"
                            ></span>
                        </button>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row md:space-x-4">
                    <div class="mt-1 w-full md:w-1/3">
                        <label for="minAmountChargers" class="block text-sm font-medium text-gray-700">Min amount of chargers</label>
                        <x-dropdown-menu id="1" :selectText="$minAmountOfChargers ?? 'Any'">
                            <x-dropdown-item :active="!$minAmountOfChargers" wire:click="$set('minAmountOfChargers', null)">
                                Any
                            </x-dropdown-item>
                            @foreach (range(1, 10) as $amount)
                            <x-dropdown-item :active="$amount === $minAmountOfChargers" wire:click="$set('minAmountOfChargers', '{{ $amount }}')">
                                {{ $amount }}
                            </x-dropdown-item>
                            @endforeach
                        </x-dropdown-menu>
                    </div>
                </div>
            </div>
            <ul wire:poll.10s role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($locations as $location)
                    <x-location.card :location="$location"/>
                @endforeach
            </ul>
            <div class="mt-6">
                {{ $locations->links() }}
            </div>
        </div>
    </div>
</div>
