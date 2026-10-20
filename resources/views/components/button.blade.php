{{--
    <x-ui::button>Cancelar</x-ui::button>
    <x-ui::button variant="primary" icon-trailing="arrow-right-01">Começar a usar</x-ui::button>
    <x-ui::button variant="ai" icon="sparkles">Revisar com IA</x-ui::button>
    <x-ui::button variant="danger">Excluir</x-ui::button>
    <x-ui::button variant="ghost" icon="more-horizontal" label="Mais ações" />
    <x-ui::button href="/docs">Documentação</x-ui::button>

    variant: secondary (padrão) · primary · ai · danger · ghost
    size:    sm · base (padrão) · lg
    shape:   rounded (padrão) · pill
--}}
@props([
    'variant' => 'secondary',
    'size' => 'base',
    'shape' => 'rounded',
    'icon' => null,
    'iconTrailing' => null,
    'label' => null,
    'href' => null,
    'type' => 'button',
])

@php
    $isIconOnly = $slot->isEmpty() && ($icon || $iconTrailing);

    $variantClasses = match ($variant) {
        'primary' => 'bg-ink text-on-ink hover:bg-ink/90',
        'ai' => 'border border-warn-line bg-warn-bg text-warn-fg hover:border-ai',
        'danger' => 'border border-risk-line bg-risk-bg text-risk-fg hover:border-risk-fg/40',
        'ghost' => 'text-ink-2 hover:bg-wrap hover:text-ink',
        default => 'border border-line bg-card text-ink hover:border-line-hover hover:bg-wrap',
    };

    $sizeClasses = match ($size) {
        'sm' => $isIconOnly ? 'size-9' : 'h-9 gap-1.5 px-3 text-sm',
        'lg' => $isIconOnly ? 'size-13' : 'h-13 gap-2.5 px-6',
        default => $isIconOnly ? 'size-11.5' : 'h-11.5 gap-2 px-5',
    };

    $iconClasses = $size === 'base' || $size === 'lg' ? 'size-4.5' : 'size-4';

    $classes = [
        'inline-flex shrink-0 cursor-pointer items-center justify-center font-medium whitespace-nowrap no-underline transition',
        'outline-none focus-visible:ring-4 focus-visible:ring-ink/10',
        'disabled:pointer-events-none disabled:opacity-50',
        $shape === 'pill' ? 'rounded-full' : ($size === 'sm' ? 'rounded-[10px]' : 'rounded-xl'),
        $variantClasses,
        $sizeClasses,
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" @if ($label) aria-label="{{ $label }}" @endif {{ $attributes->class($classes) }}>
@else
    <button type="{{ $type }}" @if ($label) aria-label="{{ $label }}" @endif {{ $attributes->class($classes) }}>
@endif
    @if ($icon)
        <x-ui::icon :name="$icon" :class="$iconClasses" />
    @endif
    {{ $slot }}
    @if ($iconTrailing)
        <x-ui::icon :name="$iconTrailing" :class="$iconClasses" />
    @endif
@if ($href)
    </a>
@else
    </button>
@endif
