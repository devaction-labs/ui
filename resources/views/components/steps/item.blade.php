{{--
    Passo da lista vertical. O slot entra embaixo da descrição (botão de ação, detalhes).

    <x-ui::steps.item state="current" label="Primeiro projeto" description="SGE · Campo Limpo">
        <x-ui::button size="sm" variant="primary">Criar projeto</x-ui::button>
    </x-ui::steps.item>

    state: done · current · error · todo (padrão)
--}}
@props([
    'label' => null,
    'description' => null,
    'state' => 'todo',
])

<li data-state="{{ $state }}" @if (in_array($state, ['current', 'error'], true)) aria-current="step" @endif {{ $attributes->class(['group/step grid grid-cols-[28px_minmax(0,1fr)] gap-3']) }}>
    <span class="flex flex-col items-center">
        <span @class([
            'flex size-6 flex-none items-center justify-center rounded-full text-on-ink',
            'bg-ink' => $state === 'done',
            'border-2 border-ink bg-card' => $state === 'current',
            'border-2 border-missing bg-card text-missing' => $state === 'error',
            'border-[1.5px] border-line-hover bg-card' => $state === 'todo',
        ])>
            @if ($state === 'done')
                <x-ui::icon name="tick-02" class="size-3.5" />
                <span class="sr-only">{{ __('Done:') }}</span>
            @elseif ($state === 'error')
                <span class="text-xs font-semibold" aria-hidden="true">!</span>
                <span class="sr-only">{{ __('Error:') }}</span>
            @endif
        </span>
        <span @class([
            'min-h-[18px] w-0.5 flex-1 group-last/step:invisible',
            'bg-ink' => $state === 'done',
            'bg-line' => $state !== 'done',
        ])></span>
    </span>
    <span class="flex min-w-0 flex-col gap-0.5 pt-0.5 pb-4">
        <span @class([
            'text-sm font-medium',
            'text-muted' => $state === 'todo',
            'text-risk-fg' => $state === 'error',
            'text-ink' => in_array($state, ['done', 'current'], true),
        ])>{{ $label }}</span>
        @if ($description)
            <span class="text-[13px] text-muted">{{ $description }}</span>
        @endif
        @if ($slot->isNotEmpty())
            <div class="mt-2">{{ $slot }}</div>
        @endif
    </span>
</li>
