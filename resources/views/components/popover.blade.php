{{--
    Painel flutuante de um x-ui::dropdown, para conteúdo livre: detalhes, filtros, formulários curtos.

    <x-ui::dropdown>
        <x-ui::button>M3 · Financeiro</x-ui::button>

        <x-ui::popover arrow role="dialog" aria-label="Detalhes do marco">
            …
        </x-ui::popover>
    </x-ui::dropdown>

    arrow: setinha apontando para o gatilho, como no design
    A largura padrão é 320 px; troque com classes (w-72, max-w-sm…).
--}}
@aware([
    'position' => 'bottom',
    'align' => 'start',
])

@props([
    'arrow' => false,
])

@php
    $isVertical = in_array($position, ['top', 'bottom'], true);

    $sideClasses = match ($position) {
        'top' => 'bottom-full mb-(--dropdown-gap)',
        'left' => 'right-full mr-(--dropdown-gap)',
        'right' => 'left-full ml-(--dropdown-gap)',
        default => 'top-full mt-(--dropdown-gap)',
    };

    $alignClasses = match (true) {
        $isVertical && $align === 'center' => 'left-1/2 -translate-x-1/2',
        $isVertical && $align === 'end' => 'right-(--dropdown-offset)',
        $isVertical => 'left-(--dropdown-offset)',
        $align === 'center' => 'top-1/2 -translate-y-1/2',
        $align === 'end' => 'bottom-(--dropdown-offset)',
        default => 'top-(--dropdown-offset)',
    };

    $arrowSideClasses = match ($position) {
        'top' => '-bottom-[6px] border-r border-b',
        'left' => '-right-[6px] border-t border-r',
        'right' => '-left-[6px] border-b border-l',
        default => '-top-[6px] border-t border-l',
    };

    $arrowAlignClasses = match (true) {
        $isVertical && $align === 'center' => 'left-1/2 -ml-[5px]',
        $isVertical && $align === 'end' => 'right-7',
        $isVertical => 'left-7',
        $align === 'center' => 'top-1/2 -mt-[5px]',
        $align === 'end' => 'bottom-4',
        default => 'top-4',
    };
@endphp

<div
    data-popover
    data-dropdown-panel
    x-show="open"
    x-cloak
    x-transition:enter="transition duration-150 ease-out motion-reduce:transition-none"
    x-transition:enter-start="opacity-0 scale-[.98]"
    x-transition:leave="transition duration-100 ease-in motion-reduce:transition-none"
    x-transition:leave-end="opacity-0"
    {{ $attributes->class([
        'absolute z-40 w-80 rounded-2xl border border-line bg-card p-4 text-left text-sm text-ink shadow-[0_16px_40px_rgb(24_24_27/0.14)]',
        $sideClasses,
        $alignClasses,
    ]) }}
>
    @if ($arrow)
        <span aria-hidden="true" class="absolute size-2.5 rotate-45 border-line bg-card {{ $arrowSideClasses }} {{ $arrowAlignClasses }}"></span>
    @endif
    <div class="relative">
        {{ $slot }}
    </div>
</div>
