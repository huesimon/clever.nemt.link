<div class="" style="">
    <div class="bg-gray-100">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="pb-6">
                <div class="flex flex-row space-x-4">
                    <div class="w-2/3">
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <div class="mt-1">
                            <input wire:model='search' type="search" name="search" id="search"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Copenhagen">
                        </div>
                    </div>
                    {{-- select with options for slow, fast, hyper --}}
                    <div class="mt-1 w-1/3">
                        <label for="kwh" class="block text-sm font-medium text-gray-700">Speed</label>
                        <select wire:model='kwh' id="kwh" name="kwh"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="null">All</option>
                            <option value="slow">Slow</option>
                            <option value="fast">Fast</option>
                            <option value="hyper">Hyper</option>
                        </select>
                    </div>
{{--
                    @if (auth()->check() || Route::currentRouteName() == 'user.favorites')
                        <div class="mt-1 w-1/3">
                            <label for="update" class="block text-sm font-medium text-gray-700">Update</label>
                            <button
                                wire:click='updateLocations'
                                id="update"
                                type="button"
                                class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Update</button>
                        </div>
                    @endif --}}
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
