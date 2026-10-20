{{--
    Agrupa skeletons com a mesma animação.

    <x-ui::skeleton.group animate="shimmer" label="Carregando PRs" class="flex items-center gap-3">
        <x-ui::skeleton class="size-9 rounded-full" />
        <div class="flex-1">
            <x-ui::skeleton.line />
            <x-ui::skeleton.line class="w-1/2" />
        </div>
    </x-ui::skeleton.group>

    animate: shimmer · pulse — repassado para todos os skeletons de dentro
    label:   texto para leitores de tela; com ele o grupo vira role="status"
--}}
@props([
    'animate' => null,
    'label' => null,
])

<div
    data-skeleton-group
    @if ($label) role="status" aria-busy="true" @endif
    {{ $attributes }}
>
    @if ($label)
        <span class="sr-only">{{ $label }}</span>
    @endif
    {{ $slot }}
</div>
