{{--
    Quem assina, para o painel lateral do visualizador.

    <x-ui::pdf-viewer.signer name="Alex" role="DevAction Labs" status="pending" />

    status: signed (Assinou) · pending (Pendente) · declined (Recusou)
--}}
@props([
    'name',
    'role' => null,
    'status' => 'pending',
    'color' => 'auto',
])

@php
    [$statusLabel, $statusColor] = match ($status) {
        'signed' => ['Assinou', 'ok'],
        'declined' => ['Recusou', 'risk'],
        default => ['Pendente', 'warn'],
    };
@endphp

<div {{ $attributes->class(['flex items-center gap-2.5 rounded-[10px] border border-line p-2.5']) }}>
    <x-ui::avatar :name="$name" :color="$color" size="sm" class="size-[30px]" />
    <div class="min-w-0">
        <div class="truncate font-medium">{{ $name }}</div>
        @if ($role)
            <div class="truncate text-xs text-muted">{{ $role }}</div>
        @endif
    </div>
    <x-ui::badge :color="$statusColor" size="sm" class="ml-auto">{{ $statusLabel }}</x-ui::badge>
</div>
