<div class="hidden md:fixed md:inset-y-0 md:z-50 md:flex md:w-72 md:flex-col">
    <!-- Sidebar component, swap this element with another sidebar if you like -->
    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-4">
        <div class="flex h-16 shrink-0 items-center">
            <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&amp;shade=500"
                alt="Your Company">
        </div>
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        <li>
                            <x-elements.navbar-link wire:navigate :link="route('home')" icon="house"
                                :active="route('home') == url()->current()">
                                Dashboard
                            </x-elements.navbar-link>
                        </li>
                        <li>
                            <x-elements.navbar-link wire:navigate :link="route('radius')" icon="globe"
                                :active="route('radius') == url()->current()">
                                Radius
                            </x-elements.navbar-link>
                        </li>
                        <li>
                            <x-elements.navbar-link wire:navigate :link="route('map')" icon="map"
                                :active="route('map') == url()->current()">
                                Map
                            </x-elements.navbar-link>
                        </li>
                        <li>
                            <x-elements.navbar-link wire:navigate :link="route('feedback')" icon="inbox"
                                :active="route('feedback') == url()->current()">
                                Feedback
                            </x-elements.navbar-link>
                        </li>
                        @auth
                        <li>
                            <x-elements.navbar-link wire:navigate :link="route('user.favorites', ['user' => auth()->user()->id ])"
                                icon="heart"
                                :active="route('user.favorites', ['user' => auth()->user()->id ]) == url()->current()">
                                Favorites
                            </x-elements.navbar-link>
                        </li>
                        @endauth
                        <li>
                            <x-elements.navbar-link wire:navigate :link="route('reports')" icon="chart-bar"
                                :active="route('reports') == url()->current()">
                                Reports
                            </x-elements.navbar-link>
                        </li>

                    </ul>
                </li>
                <li>
                    <div class="text-xs font-semibold leading-6 text-gray-400">Quick links</div>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li>
                            <a href="https://github.com/huesimon/clever.nemt.link"
                                class="text-gray-400 hover:text-white hover:bg-gray-800 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold"
                                x-state:on="Current" x-state:off="Default"
                                x-state-description="Current: &quot;bg-gray-800 text-white&quot;, Default: &quot;text-gray-400 hover:text-white hover:bg-gray-800&quot;">
                                <span
                                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 text-[0.625rem] font-medium text-gray-400 group-hover:text-white">G</span>
                                <span class="truncate">Github Repo</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://t.me/+Q8PG4iyfPVg5NzBk"
                                class="text-gray-400 hover:text-white hover:bg-gray-800 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold"
                                x-state-description="undefined: &quot;bg-gray-800 text-white&quot;, undefined: &quot;text-gray-400 hover:text-white hover:bg-gray-800&quot;">
                                <span
                                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 text-[0.625rem] font-medium text-gray-400 group-hover:text-white">T</span>
                                <span class="truncate">Telegram Channel</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://github.com/users/huesimon/projects/5"
                                class="text-gray-400 hover:text-white hover:bg-gray-800 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold"
                                x-state-description="undefined: &quot;bg-gray-800 text-white&quot;, undefined: &quot;text-gray-400 hover:text-white hover:bg-gray-800&quot;">
                                <span
                                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 text-[0.625rem] font-medium text-gray-400 group-hover:text-white">P</span>
                                <span class="truncate">Project Board</span>
                            </a>
                        </li>

                    </ul>
                </li>
            </ul>
            <ul class="mt-auto">
                <livewire:emoji-panel />
                @guest
                <li>
                    <a href="{{ route('login') }}"
                        class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-gray-800 hover:text-white">
                        Login
                    </a>
                </li>
                <li>
                    <a href="{{ route('register') }}"
                        class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-gray-800 hover:text-white">
                        Register
                    </a>
                </li>
                @endguest
                @auth
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                            class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-gray-800 hover:text-white"
                            onclick="event.preventDefault();
                            this.closest('form').submit();">
                            Logout
                        </a>
                    </form>
                </li>
                @endauth
            </ul>
        </nav>
    </div>
</div>
