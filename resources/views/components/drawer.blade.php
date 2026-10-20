{{--
    Drawer: painel lateral para editar sem sair da tela (perfil, tarefa, item de contrato).
    Usa o mesmo <dialog> do x-ui::modal: foco preso, Esc fecha, rolagem da página travada.

    <x-ui::modal.trigger name="editar-perfil">
        <x-ui::button>Editar perfil</x-ui::button>
    </x-ui::modal.trigger>

    <x-ui::drawer name="editar-perfil" heading="Editar perfil" description="Altere seus dados pessoais.">
        …
        <x-slot:footer>
            <x-ui::modal.close><x-ui::button variant="filled">Cancelar</x-ui::button></x-ui::modal.close>
            <x-ui::button variant="primary">Salvar alterações</x-ui::button>
        </x-slot:footer>
    </x-ui::drawer>

    variant: floating (padrão, descolado 12 px) · docked (colado na borda)
    side:    right (padrão) · left · bottom (folha inferior com alça)
    size:    sm (384) · md (480, padrão) · lg (640)
--}}
@props([
    'name' => null,
    'heading' => null,
    'description' => null,
    'variant' => 'floating',
    'side' => 'right',
    'size' => 'md',
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

    $isSheet = $side === 'bottom';
    $isFloating = $variant === 'floating' && ! $isSheet;
    $hasFooter = $footer && $footer->isNotEmpty();

    $widthClasses = $isSheet ? 'w-[390px]' : match ($size) {
        'sm' => 'w-[384px]',
        'lg' => 'w-[640px]',
        default => 'w-[480px]',
    };

    $dialogClasses = match (true) {
        $isSheet => 'mx-auto mt-auto mb-0 h-[min(620px,90dvh)] max-h-none open:animate-sheet-in',
        $side === 'left' && $isFloating => 'my-3 mr-auto ml-3 h-[calc(100dvh-1.5rem)] max-h-none [--flyout-from:-16px] open:animate-flyout-in',
        $side === 'left' => 'my-0 mr-auto ml-0 h-dvh max-h-dvh [--flyout-from:-16px] open:animate-flyout-in',
        $isFloating => 'my-3 mr-3 ml-auto h-[calc(100dvh-1.5rem)] max-h-none open:animate-flyout-in',
        default => 'my-0 mr-0 ml-auto h-dvh max-h-dvh open:animate-flyout-in',
    };

    $panelClasses = match (true) {
        $isSheet => 'rounded-t-[22px] border border-line',
        $isFloating => 'rounded-2xl border border-line',
        $side === 'left' => 'border-r border-line',
        default => 'border-l border-line',
    };
@endphp

<div
    data-drawer
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
        @if ($heading) x-bind:aria-labelledby="$id('ui-drawer-title')" @endif
        x-id="['ui-drawer-title']"
        @class([
            'max-w-[calc(100%-1.5rem)] overflow-visible bg-transparent p-0 text-ink',
            'backdrop:bg-[rgb(9_9_11/0.42)] backdrop:backdrop-blur-[2px] motion-reduce:open:animate-none',
            $dialogClasses,
        ])
    >
        <div {{ $panelAttributes->class([
            'flex h-full max-w-full flex-col overflow-hidden bg-card text-left shadow-(--shadow-modal)',
            $widthClasses,
            $panelClasses,
        ]) }}>
            @if ($isSheet)
                <span aria-hidden="true" class="mt-2.5 h-1 w-10 shrink-0 self-center rounded-full bg-line-hover"></span>
            @endif

            @if ($heading || $description || $closable)
                <div class="flex shrink-0 items-start gap-3 px-7 pt-7 pb-2">
                    <div class="flex min-w-0 flex-1 flex-col gap-1.5">
                        @if ($heading)
                            <h2 x-bind:id="$id('ui-drawer-title')" class="text-xl font-semibold tracking-[-0.01em]">{{ $heading }}</h2>
                        @endif
                        @if ($description)
                            <p class="text-[15px] text-muted">{{ $description }}</p>
                        @endif
                    </div>
                    @if ($closable)
                        <button
                            type="button"
                            x-on:click="open = false"
                            aria-label="Fechar"
                            class="-mt-2 -mr-2 inline-flex size-10 shrink-0 cursor-pointer items-center justify-center rounded-[10px] text-muted transition outline-none hover:bg-wrap hover:text-ink focus-visible:ring-2 focus-visible:ring-ring"
                        >
                            <x-ui::icon name="cancel-01" stroke-width="1.8" class="size-[18px]" />
                        </button>
                    @endif
                </div>
            @endif

            <div class="flex min-h-0 flex-1 flex-col gap-5 overflow-y-auto px-7 pt-4 pb-6 text-sm">
                {{ $slot }}
            </div>

            @if ($hasFooter)
                <div {{ $footer->attributes->class(['flex shrink-0 flex-wrap items-center justify-end gap-2 border-t border-line-soft px-7 pt-4 pb-6']) }}>
                    {{ $footer }}
                </div>
            @endif
        </div>
    </dialog>
</div>
