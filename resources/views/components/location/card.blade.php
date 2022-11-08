<li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow">
    <div class="flex w-full items-center justify-between space-x-6 p-6">
        <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
                <h3 class="truncate text-sm font-medium text-gray-900">{{ $location->name }}</h3>

                @if ($location->is_occupied)
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
        </div>
        <img class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-300"
            src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=60"
            alt="">
    </div>
    <div>
        <div class="-mt-px flex divide-x divide-gray-200">
            <div class="flex w-0 flex-1">
                <a href="mailto:janecooper@example.com"
                    class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center rounded-bl-lg border border-transparent py-4 text-sm font-medium text-gray-700 hover:text-gray-500">
                    <svg class="h-5 w-5 text-gray-400" x-description="Heroicon name: mini/envelope"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z">
                        </path>
                        <path
                            d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z">
                        </path>
                    </svg>
                    <span class="ml-3">Email</span>
                </a>
            </div>
            <div class="-ml-px flex w-0 flex-1">
                <a href="tel:+1-202-555-0170"
                    class="relative inline-flex w-0 flex-1 items-center justify-center rounded-br-lg border border-transparent py-4 text-sm font-medium text-gray-700 hover:text-gray-500">
                    <svg class="h-5 w-5 text-gray-400" x-description="Heroicon name: mini/phone"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Call</span>
                </a>
            </div>
        </div>
    </div>
</li>
