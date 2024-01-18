<!-- Static sidebar for desktop -->
<div class="hidden md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
    <!-- Sidebar component, swap this element with another sidebar if you like -->
    <div class="flex min-h-0 flex-1 flex-col bg-gray-800">
        <div class="flex h-16 flex-shrink-0 items-center bg-gray-900 px-4">
            {{-- <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
                alt="Your Company"> --}}
        </div>
        <div class="flex flex-1 flex-col overflow-y-auto">
            <nav class="flex-1 space-y-1 px-2 py-4">
                <x-elements.navbar-link :link="route('home')" icon="house" :active="route('home') == url()->current()">
                    Dashboard
                </x-elements.navbar-link>
                <x-elements.navbar-link :link="route('radius')" icon="globe" :active="route('radius') == url()->current()">
                    Radius
                </x-elements.navbar-link>
                <x-elements.navbar-link :link="route('map')" icon="map" :active="route('map') == url()->current()">
                    Map
                </x-elements.navbar-link>
                <x-elements.navbar-link :link="route('feedback')" icon="inbox" :active="route('feedback') == url()->current()">
                    Feedback
                </x-elements.navbar-link>

                @auth
                    <x-elements.navbar-link
                        :link="route('user.favorites', ['user' => auth()->user()->id ])"
                        icon="heart"
                        :active="route('user.favorites', ['user' => auth()->user()->id ]) == url()->current()">
                        Favorites
                    </x-elements.navbar-link>
                @endauth

                <x-elements.navbar-link :link="route('reports')" icon="chart-bar" :active="route('reports') == url()->current()">
                    Reports
                </x-elements.navbar-link>

                @guest
                <a href="{{route('register')}}"
                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Register
                </a>

                <a href="{{route('login')}}"
                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Login
                </a>
                @endguest

                @auth
                    <form method="POST" action=" {{ route('logout')}} "
                    class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        @csrf
                        <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <button type="submit">Logout</button>
                    </form>
                @endauth

            </nav>
        </div>
    </div>
</div>
