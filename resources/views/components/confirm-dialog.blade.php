{{--
    Diálogo de confirmação: ícone, título, texto e Cancelar/Confirmar, sobre o x-ui::modal.

    <x-ui::modal.trigger name="publicar">
        <x-ui::button variant="primary">Publicar review</x-ui::button>
    </x-ui::modal.trigger>

    <x-ui::confirm-dialog
        name="publicar"
        color="ai"
        icon="git-pull-request"
        heading="Publicar review no GitHub?"
        text="2 comentários vão para o PR #142."
        confirm="Publicar"
        confirm:wire:click="publish"
    />

    color:            default · ai · success · info — tom do ícone
    confirm:*         atributos repassados ao botão de confirmar (confirm:wire:click, confirm:x-on:click…)
    close-on-confirm: fecha ao confirmar (padrão); false mantém aberto para fechar pelo servidor
--}}
@props([
    'name' => null,
    'heading' => null,
    'text' => null,
    'icon' => null,
    'color' => 'default',
    'confirm' => 'Confirmar',
    'cancel' => 'Cancelar',
    'confirmVariant' => 'primary',
    'size' => 'sm',
    'closeOnConfirm' => true,
])

@php
    $confirmAttributes = new \Illuminate\View\ComponentAttributeBag(
        collect($attributes->getAttributes())
            ->filter(fn ($value, string $key): bool => str_starts_with($key, 'confirm:'))
            ->mapWithKeys(fn ($value, string $key): array => [substr($key, strlen('confirm:')) => $value])
            ->all()
    );
    $modalAttributes = $attributes->filter(fn ($value, string $key): bool => ! str_starts_with($key, 'confirm:'));

    $icon ??= match ($color) {
        'ai' => 'sparkles',
        'success' => 'checkmark-circle-02',
        default => 'information-circle',
    };

    $tileClasses = match ($color) {
        'ai' => 'border-warn-line bg-warn-bg text-accent',
        'success' => 'border-ok-line bg-ok-bg text-ok-fg',
        'info' => 'border-info-line bg-info-bg text-info-fg',
        default => 'border-line bg-wrap text-ink-2',
    };
@endphp

<x-ui::modal :name="$name" :size="$size" :closable="false" :attributes="$modalAttributes">
    <div class="flex gap-4" data-confirm-dialog>
        <span class="flex size-11 shrink-0 items-center justify-center rounded-xl border {{ $tileClasses }}">
            <x-ui::icon :name="$icon" />
        </span>
        <div class="flex min-w-0 flex-col gap-1.5">
            @if ($heading)
                <h2 class="text-lg font-semibold">{{ $heading }}</h2>
            @endif
            @if ($text)
                <p class="text-sm leading-[1.55] text-ink-3">{{ $text }}</p>
            @endif
            {{ $slot }}
        </div>
    </div>

    <x-slot:footer>
        <x-ui::modal.close>
            <x-ui::button>{{ $cancel }}</x-ui::button>
        </x-ui::modal.close>

        @if ($closeOnConfirm)
            <x-ui::modal.close>
                <x-ui::button :variant="$confirmVariant" :attributes="$confirmAttributes">{{ $confirm }}</x-ui::button>
            </x-ui::modal.close>
        @else
            <x-ui::button :variant="$confirmVariant" :attributes="$confirmAttributes">{{ $confirm }}</x-ui::button>
        @endif
    </x-slot:footer>
</x-ui::modal>
