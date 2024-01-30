<div class="" style="">
    <div class="bg-gray-100">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="pb-6">
                <div class="flex flex-row space-x-4">
                    <div class="w-2/3">
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <div class="mt-1">
                            <input wire:model.live='search' type="search" name="search" id="search"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Copenhagen">
                        </div>
                    </div>
                    {{-- select with options for slow, fast, hyper --}}
                    <div class="mt-1 w-1/3">
                        <label for="kwh" class="block text-sm font-medium text-gray-700">Speed</label>
                        <select wire:model.live='kwh' id="kwh" name="kwh"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="null">All</option>
                            <option value="slow">Slow</option>
                            <option value="fast">Fast</option>
                            <option value="hyper">Hyper</option>
                        </select>
                    </div>
                    <div class="mt-1 w-1/3">
                        <label for="kwh" class="block text-sm font-medium text-gray-700">Parking Type</label>
                        <x-dropdown id="1">
                            @foreach (\App\Enums\ParkingTypes::cases() as $type)
                            <x-dropdown-item :active="$type === $parkingType">
                                <button
                                    {{-- wire click and parse enunm --}}
                                    wire:click="$set('parkingType', '{{ $type }}')"
                                >
                                    {{ $type }}
                                </button>
                            </x-dropdown-item>
                            @endforeach
                        </x-dropdown>
                    </div>
                </div>
                {{--
                    Dropdown for ParkingType
                     --}}
                <div class="flex flex-row space-x-4">
                    <div class="mt-1 w-1/3">
                        <label for="kwh" class="block text-sm font-medium text-gray-700">Parking Type</label>
                        <select wire:model.live='parkingType' id="parkingType" name="parkingType"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">All</option>
                            @foreach (\App\Enums\ParkingTypes::cases() as $parkingType)
                                <option value="{{ $parkingType }}">{{ $parkingType }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Toggle -->
                        <div
                        x-data="{ value: $wire.get('onlyClever') }"
                        class="flex items-center justify-center"
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
