<div class="px-4 sm:px-6 lg:px-8">
    <div class="mt-8 flow-root">
        {{$search}}
        {{-- <x-inputs.input-with-leading-icon wire:model.live='search'/> --}}

        <input
            type="text"
            wire:model.live="search"
            class="block w-full rounded-md border-0 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            placeholder="">


        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="relative">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Name</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Address</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Is Raoming Allowed</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Is Public Visible</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($locations as $location)
                            <tr wire:key="{{ $location->external_id }}">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                    {{ $location->name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $location->address->address }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $location->is_roaming_allowed ? 'Yes' : 'No' }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $location->is_public_visible }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div wire:loading class="absolute inset-0 bg-white opacity-50">
                    </div>
                    <div wire:loading.flex class="flex justify-center items-center absolute inset-0">
                        <x-icons.spinner class="text-gray-400" :size=16/>
                    </div>
                </div>
            </div>
        </div>
        {{ $locations->links('livewire.report.pagination') }}
    </div>
</div>
