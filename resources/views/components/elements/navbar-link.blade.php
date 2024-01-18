@props([
    'active' => false,
    'link' => '#',
    'icon' => false])

<a href="{{ $link }}"
    @if ($active)
        class="bg-gray-900 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md"
    @else
        class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md"
    @endif
    >
        <!-- Heroicon name: outline/calendar -->
        @if ($icon)
            <x-dynamic-component :component="'icons.' . $icon"/>
        @endif
        {{ $slot }}
</a>
