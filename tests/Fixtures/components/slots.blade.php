{{--
    Com slots.

    <x-slot:actions>botões</x-slot:actions>
--}}
<div>
    @if (isset($footer)) {{ $footer }} @endif
    {{ $attributes->get('data-x') }} {{ $attributes->has('icon:trailing') }} {{ $attributes->get('data-x') }}
</div>
