@props(['label' => ''])

<!-- Radio -->
<div x-data="{ value: 'laravel' }" x-radio x-modelable="value" {{ $attributes }}>
    <!-- Radio Label -->
    <label x-radio:label class="sr-only">{{ $label }}: <span x-text="value"></span></label>

    <!-- Options -->
    {{ $slot }}
</div>
