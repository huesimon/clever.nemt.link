@props(['value', 'class', 'classChecked', 'classNotChecked'])

<div
    x-radio:option
    value="{{ $value }}"
    {{ $attributes }}
    :class="{
        '{{ $class }}': true,
        '{{ $classChecked }}': $radioOption.isChecked,
        '{{ $classNotChecked }}': ! $radioOption.isChecked,
    }"
>
    {{ $slot }}
</div>
