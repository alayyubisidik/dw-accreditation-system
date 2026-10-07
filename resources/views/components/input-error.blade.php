@props(['messages'])

@if ($messages)
    @foreach ((array) $messages as $message)
        <span style="display: block; color: #dc3545; margin-top: 3px; margin-bottom: 1px;"
            class="{{ $attributes->get('class') }}">
            {{ $message }}
        </span>
    @endforeach
@endif
