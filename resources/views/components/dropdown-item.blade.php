@props(['active' => false])
<!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
<a
    {{ $attributes }}
    href="#"
    @class([
        'block px-4 py-2 text-sm text-gray-700',
        'bg-gray-100 text-gray-900' => $active,
        'text-gray-700' => ! $active,
    ])
    role="menuitem"
    tabindex="-1"
    id="menu-item-0"
>
    {{ $slot }}
</a>
