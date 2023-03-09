<li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow">
    <div class="flex w-full items-center justify-between space-x-6 p-6">
        <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
                <h3 class="truncate text-sm font-medium text-gray-900">{{ $location->name }}</h3>

                @if(!$location->is_public)
                    <span class="flex-shrink-0 inline-block px-2 py-0.5 text-yellow-800 text-xs font-medium bg-yellow-100 rounded-full">
                        In Proximity
                    </span>
                @elseif ($location->is_occupied)
                    <span class="flex-shrink-0 inline-block px-2 py-0.5 text-red-800 text-xs font-medium bg-red-100 rounded-full">
                        Occupied
                    </span>
                @else
                    <span class="flex-shrink-0 inline-block px-2 py-0.5 text-green-800 text-xs font-medium bg-green-100 rounded-full">
                        Vacant
                    </span>
                @endif

            </div>
            <p class="mt-1 truncate text-sm text-gray-500"> {{$location->available_chargers_count}} / {{$location->total_chargers_count}} </p>
            {{-- @foreach ($location->chargers as $charger)
            @if ($charger->is_occupied)
            <p class="mt-1 truncate text-sm text-gray-500"> #{{$loop->index}}: {{$charger->status}} </p>
            <p class="mt-1 truncate text-sm text-gray-500"> {{$charger->current_session}} minutes </p>
                @endif
            @endforeach --}}
        </div>
        {{-- <img class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-300"
            src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=60"
            alt=""> --}}
    </div>
    <div>
        <div class="-mt-px flex divide-x divide-gray-200">
            <div class="-ml-px flex w-0 flex-1">
                <a href="{{ "https://www.google.com/maps/search/?api=1&query=" . str($location->coordinates)->remove(' ')  }}"
                    target="_blank"
                    class="relative inline-flex w-0 flex-1 items-center justify-center rounded-br-lg border border-transparent py-4 text-sm font-medium text-gray-700 hover:text-gray-500">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                      </svg>
                    <span class="ml-3">Directions</span>
                </a>
            </div>
            {{-- <div class="-ml-px flex w-0 flex-1">
                <a href="#"
                    class="relative inline-flex w-0 flex-1 items-center justify-center rounded-br-lg border border-transparent py-4 text-sm font-medium text-gray-700 hover:text-gray-500">
                    <svg class="h-5 w-5 text-gray-400" x-description="Heroicon name: mini/phone"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Call</span>
                </a>
            </div> --}}
            <x-elements.modal :title="$location->name">
                <x-slot:button>
                    <button type="submit"
                        class="relative inline-flex w-0 flex-1 items-center justify-center rounded-br-lg border border-transparent py-4 text-sm font-medium text-gray-700 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="ml-3">Chargers</span>
                    </button>
                </x-slot:button>

                <x-slot:content>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                    #{{ $location->id }}: {{ $location->name }}
                                </h3>
                                <div class="flex flex-col space-y-2 mt-2">
                                    @foreach ($location->chargers as $charger)
                                        {{-- display id and how long the charging_session is --}}
                                        <p class="text-sm">
                                            {{ $charger->readable_id }} - <span class="inline-flex items-center rounded-full {{$charger->session_color}} px-2.5 py-0.5 text-xs font-medium text-indigo-800">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-indigo-400" fill="currentColor" viewBox="0 0 8 8">
                                                  <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ $charger->current_session }}
                                              </span>
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot:content>


            </x-elements.modal>
        </div>



    </div>
</li>
