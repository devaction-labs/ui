{{--
    Botão (API no estilo Flux). Renderiza <a> quando recebe href, senão <button>.

    <x-ui::button>Cancelar</x-ui::button>
    <x-ui::button variant="primary">Solicitar review</x-ui::button>
    <x-ui::button variant="ghost">Ver detalhes</x-ui::button>
    <x-ui::button variant="danger" wire:click="delete">Excluir projeto</x-ui::button>
    <x-ui::button variant="ai" icon="sparkles">Revisar com IA</x-ui::button>
    <x-ui::button variant="primary" icon:trailing="arrow-right-01" href="/docs">Começar a usar</x-ui::button>
    <x-ui::button icon="more-horizontal" aria-label="Mais ações" />
    <x-ui::button variant="ghost" size="sm" icon="cancel-01" inset="top bottom" aria-label="Fechar" />

    variant: outline (padrão) · primary · filled · danger · ghost · subtle · ai · info
    size:    xs (24) · sm (32) · base (38, padrão) · lg (46)
    square:  força o formato quadrado (automático quando só tem ícone)
    inset:   top · bottom · left · right — margem negativa para alinhar botões ghost ao texto
    loading: automático com wire:click (o Livewire marca data-loading); true força, false desliga
--}}
@props([
    'variant' => 'outline',
    'size' => 'base',
    'icon' => null,
    'iconTrailing' => null,
    'square' => null,
    'inset' => null,
    'loading' => null,
    'disabled' => false,
    'href' => null,
    'type' => 'button',
])

@php
    $iconTrailing ??= $attributes->get('icon:trailing');
    $attributes = $attributes->except(['icon:trailing']);

    $square ??= $slot->isEmpty() && ($icon || $iconTrailing);

    $variantClasses = match ($variant) {
        'primary' => 'border-ink bg-ink text-on-ink hover:bg-ink/90',
        'filled' => 'border-transparent bg-wrap text-ink hover:bg-line-soft',
        'danger' => 'border-risk-line bg-risk-bg text-risk-fg hover:border-risk-fg/40',
        'ghost' => 'border-transparent text-ink-2 hover:bg-wrap hover:text-ink',
        'subtle' => 'border-transparent text-muted hover:bg-wrap hover:text-ink',
        'ai' => 'border-warn-line bg-warn-bg text-warn-fg hover:border-ai',
        'info' => 'border-info-line bg-info-bg text-info-fg hover:border-info-fg/40',
        default => 'border-line bg-card text-ink hover:border-line-hover hover:bg-wrap',
    };

    [$heightClasses, $squareClasses, $paddingClasses, $textClasses, $radiusClasses, $iconClasses] = match ($size) {
        'xs' => ['h-6', 'w-6', 'px-2', 'text-xs', 'rounded-md', 'size-3.5'],
        'sm' => ['h-8', 'w-8', 'px-3', 'text-[13px]', 'rounded-[9px]', 'size-3.5'],
        'lg' => ['h-11.5', 'w-11.5', 'px-[18px]', 'text-[15px]', 'rounded-xl', 'size-4.5'],
        default => ['h-9.5', 'w-9.5', 'px-3.5', 'text-sm', 'rounded-[10px]', 'size-4'],
    };

    $insetClasses = collect(preg_split('/\s+/', trim((string) $inset)))
        ->filter()
        ->map(fn (string $side): string => match ($side) {
            'top' => $size === 'xs' ? '-mt-1' : '-mt-2',
            'bottom' => $size === 'xs' ? '-mb-1' : '-mb-2',
            'left' => $size === 'xs' ? '-ml-1' : '-ml-2',
            'right' => $size === 'xs' ? '-mr-1' : '-mr-2',
            default => '',
        })
        ->implode(' ');

    $classes = [
        'group/button relative inline-flex shrink-0 cursor-pointer items-center justify-center gap-2 border font-medium whitespace-nowrap no-underline transition',
        'outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-wrap',
        'disabled:cursor-not-allowed disabled:opacity-45 aria-disabled:pointer-events-none aria-disabled:opacity-45',
        'data-loading:pointer-events-none',
        $heightClasses,
        $textClasses,
        $radiusClasses,
        $variantClasses,
        $square ? $squareClasses : $paddingClasses,
        $insetClasses,
    ];

    $showsLoading = $loading !== false;
    $spinnerSize = in_array($size, ['xs', 'sm'], true) ? 'xs' : 'sm';
    $iconSwapClasses = $showsLoading ? ' group-data-loading/button:hidden' : '';
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        @if ($disabled) aria-disabled="true" tabindex="-1" @endif
        @if ($loading === true) data-loading aria-busy="true" @endif
        {{ $attributes->class($classes) }}
    >
@else
    <button
        type="{{ $type }}"
        @disabled($disabled || $loading === true)
        @if ($loading === true) data-loading aria-busy="true" @endif
        {{ $attributes->class($classes) }}
    >
@endif
    @if ($showsLoading && ($icon || ! $iconTrailing))
        <span class="hidden shrink-0 group-data-loading/button:inline-flex"><x-ui::spinner :size="$spinnerSize" color="current" /></span>
    @endif
    @if ($icon)
        <x-ui::icon :name="$icon" :class="$iconClasses.$iconSwapClasses" />
    @endif
    {{ $slot }}
    @if ($iconTrailing)
        @if ($showsLoading && ! $icon)
            <span class="hidden shrink-0 group-data-loading/button:inline-flex"><x-ui::spinner :size="$spinnerSize" color="current" /></span>
        @endif
        <x-ui::icon :name="$iconTrailing" :class="$iconClasses.$iconSwapClasses" />
    @endif
@if ($href)
    </a>
@else
    </button>
@endif
