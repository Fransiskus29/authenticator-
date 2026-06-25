@props(['messages' => []])

@if ($messages)
    <ul {{ $attributes->class(['text-error text-xs mt-1 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
