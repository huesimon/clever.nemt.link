@props(['link' => false])
<button
    type="button"
    class="rounded-md border border-gray-200 bg-white px-5 py-2.5"
    {{ $attributes }}
    >
    @if ($link)
        <a href="{{ $link }}">
            {{ $slot }}
        </a>
    @else
    {{ $slot }}
    @endif
</button>
