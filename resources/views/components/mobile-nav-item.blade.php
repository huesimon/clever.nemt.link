@props(['active', 'icon' => false, 'link'])
<a href="{{ $link }}"
class="{{ $active ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-base font-medium rounded-md">
@if ($icon)
    <x-dynamic-component :component="'icons.' . $icon"/>
@endif
{{ $slot }}
</a>
