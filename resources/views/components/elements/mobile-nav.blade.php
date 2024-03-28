 <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
 <div
    x-data="{
        open: false,
        toggle() {
            console.log('toggle', this.open)
            if (this.open) {
                return this.close()
            }

            {{-- this.$refs.button.focus() --}}

            this.open = true
        },
        close(focusAfter) {
            if (! this.open) return

            this.open = false

            focusAfter && focusAfter.focus()
        }
    }"
    @foo.window="toggle()"
    {{-- x-on:keydown.escape.prevent.stop="close($refs.button)" --}}
    {{-- x-on:focusin.window="! $refs.panel.contains($event.target) && close()" --}}
    x-id="['mobile-nav-button']">

    <div class="relative z-40 md:hidden" role="dialog" aria-modal="true">
        <!--
     Off-canvas menu backdrop, show/hide based on off-canvas menu state.

     Entering: "transition-opacity ease-linear duration-300"
      From: "opacity-0"
      To: "opacity-100"
     Leaving: "transition-opacity ease-linear duration-300"
      From: "opacity-100"
      To: "opacity-0"
     -->
        <div
            style="display: none;"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-show="open"
            x-ref="panel"
            {{-- x-on:click.outside="close($refs.button)" --}}
            :id="$id('mobile-nav-button')"
            class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
        <div
            style="display: none;"
            {{-- x-ref="panel" --}}
            x-show="open"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            {{-- x-on:click="toggle()" --}}
            {{-- x-on:click.outside="close($refs.button)"
            :id="$id('dropdown-button')" --}}
            class="fixed inset-0 z-40 flex">
            <!--
      Off-canvas menu, show/hide based on off-canvas menu state.
      Entering: "transition ease-in-out duration-300 transform"
        From: "-translate-x-full"
        To: "translate-x-0"
      Leaving: "transition ease-in-out duration-300 transform"
        From: "translate-x-0"
        To: "-translate-x-full"
     -->
            <div class="relative flex w-full max-w-xs flex-1 flex-col bg-gray-800 pt-5 pb-4">
                <!--
        Close button, show/hide based on off-canvas menu state.
        Entering: "ease-in-out duration-300"
          From: "opacity-0"
          To: "opacity-100"
        Leaving: "ease-in-out duration-300"
          From: "opacity-100"
          To: "opacity-0"
      -->
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button \
                        {{-- type="button" --}}
                        {{-- x-ref="button" --}}
                        x-on:click="toggle()"
                        :aria-expanded="open"
                        :aria-controls="$id('mobile-nav-button')"
                        class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Close sidebar</span>
                        <!-- Heroicon name: outline/x-mark -->
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex flex-shrink-0 items-center px-4">
                    {{-- <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
                        alt="Your Company"> --}}
                </div>
                <div class="mt-5 h-0 flex-1 overflow-y-auto">
                    <nav class="space-y-1 px-2">
                        <x-mobile-nav-item :link="route('home')" icon="house" :active="route('home') == url()->current()">
                            Dashboard
                        </x-mobile-nav-item>
                        <x-mobile-nav-item :link="route('radius')" icon="globe" :active="route('radius') == url()->current()">
                            Radius
                        </x-mobile-nav-item>
                        <x-mobile-nav-item :link="route('map')" icon="map" :active="route('map') == url()->current()">
                            Map
                        </x-mobile-nav-item>
                        <x-mobile-nav-item
                            :link="auth()->user() ? route('user.favorites', ['user' => auth()->user()->id ]) : route('login') "
                            icon="heart"
                            :active="auth()->user() ? route('user.favorites', ['user' => auth()->user()->id ]) == url()->current() : false">
                            Favorites
                        </x-mobile-nav-item>

                        <x-mobile-nav-item :link="route('feedback')" icon="inbox" :active="route('feedback') == url()->current()">
                            Feedback
                        </x-mobile-nav-item>

                        <x-mobile-nav-item :link="route('reports')" icon="chart-bar" :active="route('reports') == url()->current()">
                            Reports
                        </x-mobile-nav-item>
                        @guest
                            <x-mobile-nav-item :link="route('register')" :active="route('register') == url()->current()">
                                Register
                            </x-mobile-nav-item>
                            <x-mobile-nav-item :link="route('login')" icon="user-circle" :active="route('login') == url()->current()">
                                Login
                            </x-mobile-nav-item>
                        @endguest
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                                    Logout
                                </button>
                            </form>
                        @endauth
                    </nav>
                </div>
            </div>
            <div class="w-14 flex-shrink-0" aria-hidden="true">
                <!-- Dummy element to force sidebar to shrink to fit close icon -->
            </div>
        </div>
     </div>
 </div>
