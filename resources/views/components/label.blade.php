{{--
    Rótulo de campo.

    <x-ui::label for="email">E-mail</x-ui::label>
    <x-ui::label badge="Opcional">Telefone</x-ui::label>
    <x-ui::label>Prazo <x-slot:trailing><x-ui::kbd>⌘D</x-ui::kbd></x-slot:trailing></x-ui::label>
--}}
@props([
    'badge' => null,
    'trailing' => null,
])

<label {{ $attributes->class(['flex items-center gap-2 text-sm font-medium text-ink']) }}>
    <span>{{ $slot }}</span>
    @if ($badge)
        <span class="rounded-md bg-wrap px-1.5 py-0.5 text-[11px] font-normal text-muted">{{ $badge }}</span>
    @endif
    @if ($trailing)
        <span class="ml-auto font-normal text-muted">{{ $trailing }}</span>
    @endif
</label>
