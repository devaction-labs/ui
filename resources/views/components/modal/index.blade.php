{{--
    Modal (API no estilo Flux) sobre <dialog>: foco preso, Esc fecha e a rolagem da página trava.

    <x-ui::modal.trigger name="publicar">
        <x-ui::button variant="primary">Publicar review</x-ui::button>
    </x-ui::modal.trigger>

    <x-ui::modal name="publicar" size="sm">
        …
        <x-slot:footer>
            <x-ui::modal.close><x-ui::button>Cancelar</x-ui::button></x-ui::modal.close>
            <x-ui::button variant="primary" wire:click="publish">Publicar</x-ui::button>
        </x-slot:footer>
    </x-ui::modal>

    <x-ui::modal wire:model="showConfirm">…</x-ui::modal>     estado ligado ao Livewire
    <x-ui::modal name="perfil" flyout position="left">…</x-ui::modal>

    size:     sm (400) · md (520, padrão) · lg (720)
    flyout:   abre colado na lateral; position right (padrão) · left · bottom
    variant:  default · floating (flyout descolado da borda) · bare (sem moldura nem padding)
    eventos:  close (fechou por qualquer motivo) · cancel (Esc ou clique fora)
    JS:       $modal('nome').show() · $modals().close() · DevActionUI.modal('nome').close()
--}}
@props([
    'name' => null,
    'size' => 'md',
    'flyout' => false,
    'position' => 'right',
    'variant' => 'default',
    'dismissible' => true,
    'escapable' => true,
    'closable' => true,
    'footer' => null,
])

@php
    $wrapperAttributes = $attributes->filter(
        fn ($value, string $key): bool => str_starts_with($key, 'wire:') || str_starts_with($key, 'x-on:') || str_starts_with($key, '@')
    );
    $panelAttributes = $attributes->filter(
        fn ($value, string $key): bool => ! (str_starts_with($key, 'wire:') || str_starts_with($key, 'x-on:') || str_starts_with($key, '@'))
    );

    $isBare = $variant === 'bare';
    $isFloating = $variant === 'floating';
    $hasFooter = $footer && $footer->isNotEmpty();

    $widthClasses = match ($size) {
        'sm' => 'w-[400px]',
        'lg' => 'w-[720px]',
        default => 'w-[520px]',
    };

    $dialogClasses = $flyout
        ? match ($position) {
            'left' => 'my-0 mr-auto ml-0 h-dvh max-h-dvh [--flyout-from:-16px]',
            'bottom' => 'mx-0 mt-auto mb-0 w-full max-w-full [--flyout-from:0px]',
            default => 'my-0 mr-0 ml-auto h-dvh max-h-dvh',
        }
        : 'm-auto max-h-[calc(100dvh-2rem)]';
@endphp

<div
    data-modal
    @if ($name) data-modal-name="{{ $name }}" @endif
    x-data="uiModal({ name: @js($name), dismissible: @js((bool) $dismissible), escapable: @js((bool) $escapable) })"
    x-modelable="open"
    x-on:ui-modal-close-self="open = false"
    class="contents"
    {{ $wrapperAttributes }}
>
    <dialog
        x-ref="dialog"
        wire:ignore.self
        x-on:close="closed()"
        x-on:cancel="cancel($event)"
        x-on:click="clickOutside($event)"
        @class([
            'max-w-[calc(100%-2rem)] overflow-visible bg-transparent p-0 text-ink',
            'backdrop:bg-[rgb(9_9_11/0.42)] backdrop:backdrop-blur-[2px]',
            $flyout ? 'open:animate-flyout-in' : 'open:animate-modal-in',
            'motion-reduce:open:animate-none',
            $dialogClasses,
            $isFloating && $flyout ? 'p-2' : '',
        ])
    >
        <div {{ $panelAttributes->class([
            'relative flex max-h-[inherit] flex-col overflow-hidden text-left',
            $flyout && $position !== 'bottom' ? 'h-full max-w-[min(100vw,480px)]' : '',
            $flyout && $position === 'bottom' ? 'max-h-[85dvh]' : '',
            ! $flyout ? $widthClasses : '',
            $flyout && $position !== 'bottom' && ! $isFloating ? 'w-[420px]' : '',
            $isFloating && $flyout ? 'h-full w-[420px] rounded-[18px]' : '',
            'border border-line bg-card shadow-(--shadow-modal)' => ! $isBare,
            'rounded-[18px]' => ! $isBare && ! $flyout,
            'rounded-t-[18px]' => ! $isBare && $flyout && $position === 'bottom',
        ]) }}>
            @if ($closable && ! $isBare)
                <button
                    type="button"
                    x-on:click="open = false"
                    aria-label="Fechar"
                    class="absolute top-3.5 right-3.5 z-10 inline-flex size-[38px] cursor-pointer items-center justify-center rounded-[10px] text-muted transition outline-none hover:bg-wrap hover:text-ink focus-visible:ring-2 focus-visible:ring-ring"
                >
                    <x-ui::icon name="cancel-01" stroke-width="1.8" class="size-[18px]" />
                </button>
            @endif

            <div @class(['min-h-0 flex-1 overflow-y-auto', 'p-[26px]' => ! $isBare, 'pr-14' => $closable && ! $isBare])>
                {{ $slot }}
            </div>

            @if ($hasFooter)
                <div {{ $footer->attributes->class(['flex flex-wrap items-center justify-end gap-2 border-t border-line-soft bg-subtle px-5 py-3.5']) }}>
                    {{ $footer }}
                </div>
            @endif
        </div>
    </dialog>
</div>
