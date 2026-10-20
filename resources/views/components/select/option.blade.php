{{-- Opção de um x-ui::select nativo --}}
@props([
    'value' => null,
])

<option {{ $value !== null ? $attributes->merge(['value' => $value]) : $attributes }}>{{ $slot }}</option>
