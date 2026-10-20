{{-- Grupo de notificações de um dia: <x-ui::notification-center.group heading="Hoje">…</x-ui::notification-center.group> --}}
@props([
    'heading',
])

<div data-notification-group x-show="groupHasItems($el)">
    <div class="px-4 pt-2.5 pb-1 font-mono text-[11px] tracking-[0.06em] text-muted uppercase">{{ $heading }}</div>
    {{ $slot }}
</div>
