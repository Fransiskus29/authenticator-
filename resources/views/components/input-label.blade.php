@props(['value' => ''])

<label {{ $attributes->class(['block text-label-sm font-label-sm text-on-surface mb-base']) }}>
    {{ $value ?? $slot }}
</label>
