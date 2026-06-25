@props(['value' => ''])

<input {{ $attributes->merge(['value' => $value])->class(['w-full bg-surface-container-lowest border border-outline-variant rounded-lg px-sm py-2 text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all']) }}>
