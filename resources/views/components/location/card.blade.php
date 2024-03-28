<li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow">
    <div class="flex w-full items-center justify-between space-x-6 p-6">
        <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
                <h3 class="truncate text-sm font-medium text-gray-900">{{ $location->name }}</h3>
                {{-- favorite button --}}
                <div class="flex-shrink-0">
                    <button type="button"
                        wire:click="toggleFavorite({{$location}})"
                        class="relative inline-flex items-center justify-center h-6 w-6 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
                        aria-expanded="false">
                        <span class="sr-only">Add to favorite</span>
                        <svg
                            @if ($location->is_favorite)
                                fill="currentColor"
                            @else
                                fill="none"
                            @endif
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"></path>
                          </svg>
                    </button>
                </div>
            </div>
            <p class="mt-1 truncate text-sm text-gray-500"> {{$location->available_chargers_count}} / {{$location->total_chargers_count}} </p>
            {{-- @foreach ($location->chargers as $charger)
            @if ($charger->is_occupied)
            <p class="mt-1 truncate text-sm text-gray-500"> #{{$loop->index}}: {{$charger->status}} </p>
            <p class="mt-1 truncate text-sm text-gray-500"> {{$charger->current_session}} minutes </p>
                @endif
            @endforeach --}}
        </div>
        <div class="flex flex-col space-y-2">
            @if(!$location->is_public)
                <x-badges.flat-with-dot color="yellow" text="InProximity" />
            @elseif ($location->is_occupied)
                <x-badges.flat-with-dot color="red" text="Occupied" />
            @else
                <x-badges.flat-with-dot color="green" text="Available" />
            @endif

            @if ($location->isClever)
                <x-badges.flat-with-dot color="blue" text="Clever" />
            @else
                <x-badges.flat-with-dot color="gray" text="Other Network" />
            @endif

            @if ($location->partner_status?->isIncluded())
                <x-badges.flat-with-dot color="emerald" text="Included" />
            @endif

        </div>
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
                                    {{--  --}}
                                </h3>
                                <div class="flex flex-col space-y-2 mt-2">
                                    @foreach ($location->chargers as $charger)
                                        {{-- display id and how long the charging_session is --}}
                                        <div class="flex flex-col md:flex-row gap-4 text-sm">
                                            @if ($charger->has_star) <span> ⭐ </span> @endif {{ $charger->readable_id }} - <span class="inline-flex items-center rounded-full {{$charger->session_color}} px-2.5 py-0.5 text-xs font-medium text-indigo-800">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-indigo-400" fill="currentColor" viewBox="0 0 8 8">
                                                  <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ $charger->current_session }}
                                              </span>

                                              <span class="inline-flex items-center rounded-full {{ $charger->kw_color }} px-2.5 py-0.5 text-xs font-medium text-indigo-800">
                                                {{ $charger->plug_type }}: {{ $charger->kw }} kW
                                              </span>
                                              @if ($charger->mightBeOutOfOrder)
                                                  <span class="inline-flex items-center">
                                                        <x-icons.exclamation-triangle class="w-6 h-6 text-red-500"/>
                                                  </span>
                                              @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot:content>
                <x-slot:buttons>
                    <x-old_button x-on:click="open = false" :link="route('location.chart', $location)">
                        View Chart
                    </x-old_button>

                    <x-old_button x-on:click="open = false">
                        Cancel
                    </x-old_button>
                </x-slot:buttons>
            </x-elements.modal>
        </div>



    </div>
</li>
