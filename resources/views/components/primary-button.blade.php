@props(['disabled' => false])

<button {{ $disabled ? 'disabled' : '' }} {{ $attributes->class(['inline-flex items-center px-md py-sm bg-primary text-on-primary border border-transparent rounded-lg font-label-sm text-label-sm tracking-wide hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
