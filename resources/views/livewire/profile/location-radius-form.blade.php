<x-jet-action-section submit="updateRadiusInformation">
    <x-slot name="title">
        {{ __('Location Radius') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s location radius.') }}
    </x-slot>

    <x-slot name="content">
        <div>
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Provide the corrdinates</h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">You will get regular updates of new and changing locations in these areas.</p>
                <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Latitude</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Longitude</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Radius</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Name</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                        <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @forelse ($radii as $radius)
                        <tr>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$radius->lat}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$radius->lng}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"> {{ $radius->radiusForHumans }} </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"> {{ $radius->name ?? 'Not set' }} </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit<span class="sr-only">, Lindsay Walton</span></a>
                            </td>
                            </tr>
                        @if($loop->last)
                            </tbody>
                            </table>
                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-2 sm:col-start-1">
                                    <label for="lat" class="block text-sm font-medium leading-6 text-gray-900">{{ __('Latitude') }}</label>
                                    <div class="mt-2">
                                        <input wire:model='lat' type="text" name="lat" id="lat" autocomplete="address-level2"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @error('lat')
                                                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="longitude" class="block text-sm font-medium leading-6 text-gray-900">Longitude</label>
                                    <div class="mt-2">
                                        <input wire:model='lng' type="text" name="longitude" id="longitude" autocomplete="address-level1"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @error('lng')
                                                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                                            @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="radius" class="block text-sm font-medium leading-6 text-gray-900">Radius in meters</label>
                                    <div class="mt-2">
                                        <input wire:model='radius' type="text" name="radius" id="longitude" autocomplete="address-level1"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @error('radius')
                                                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                                            @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                                    <div class="mt-2">
                                        <input wire:model='name' type="text" name="name" id="name" autocomplete="name" placeholder="Home or work"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @error('name')
                                                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                                            @enderror
                                    </div>
                                </div>
                                @success
                                    <span class="mt-2 text-sm text-green-600" id="success">{{ session()->get('success') }}</span>
                                @endif
                            </div>
                        @endif
                        <x-jet-button wire:click='save' class="mt-8 sm:col-span-2 sm:col-start-5">
                            {{ __('Save') }}
                        </x-jet-button>
                    @empty
                        empty
                        {{-- Input field and save button --}}
                        <div class="col-span-6 sm:col-span-4">
                            <x-jet-label for="radius" value="{{ __('Radius') }}" />
                            <x-jet-input id="radius" type="text" class="mt-1 block w-full" wire:model.defer="radiusCollection.0.radius" autocomplete="radius" />
                        </div>
                    @endforelse
        </div>
    </x-slot>
</x-jet-action-section>
