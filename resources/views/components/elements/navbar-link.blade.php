@props([
    'active' => false,
    'link' => '#',
    'icon' => false])

<a href="{{ $link }}"
    {{ $attributes }}
    @if ($active)
        class="bg-gray-800 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md"
    @else
        class="text-gray-400 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md"
    @endif
    >
        @if ($icon)
            <x-dynamic-component :component="'icons.' . $icon"/>
        @endif
        {{ $slot }}
</a>
