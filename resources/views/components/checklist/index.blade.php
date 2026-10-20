{{--
    Checklist com progresso: conta os itens marcados e pode esconder os concluídos.

    <x-ui::checklist heading="Pronto para merge">
        <x-ui::checklist.item wire:model.live="checks.multi" description="Carlos">Lançamento e baixa no mesmo Multi</x-ui::checklist.item>
        <x-ui::checklist.item checked>Rollback se o recibo falhar</x-ui::checklist.item>
        <x-slot:footer><x-ui::button size="sm" variant="ghost" icon="add-01">Adicionar um item</x-ui::button></x-slot:footer>
    </x-ui::checklist>

    <x-ui::checklist :progress="false">…</x-ui::checklist>   só os itens, para usar dentro de um comentário

    heading:  título (com ele vêm o botão de esconder concluídos e a barra de progresso)
    progress: mostra a barra e a porcentagem (padrão true quando há heading)
    Slots:    actions (ao lado do título) · footer (abaixo dos itens)
--}}
@props([
    'heading' => null,
    'progress' => null,
])

@php
    $progress ??= $heading !== null;
@endphp

<div
    data-checklist
    x-data="{
        hide: false,
        total: 0,
        done: 0,
        sync() {
            const boxes = [...this.$el.querySelectorAll('[data-checklist-item] input[type=checkbox]')];
            this.total = boxes.length;
            this.done = boxes.filter((box) => box.checked).length;
        },
        get percent() { return this.total ? Math.round(this.done / this.total * 100) : 0 },
    }"
    x-init="$nextTick(() => sync())"
    x-on:change="sync()"
    {{ $attributes->class(['flex flex-col gap-2.5']) }}
>
    @if ($heading)
        <div class="flex items-center gap-2">
            <x-ui::icon name="checkmark-square-02" class="size-4 text-muted" />
            <span class="font-semibold text-ink">{{ $heading }}</span>
            <span class="ml-auto flex items-center gap-1.5">
                {{ $actions ?? '' }}
                <x-ui::button size="sm" x-on:click="hide = ! hide" x-show="done > 0">
                    <span x-text="hide ? @js(__('Show checked items')) : @js(__('Hide checked items'))">{{ __('Hide checked items') }}</span>
                </x-ui::button>
            </span>
        </div>
    @endif

    @if ($progress)
        <div class="flex items-center gap-2.5">
            <span class="w-9 font-mono text-[11px] text-muted" x-text="percent + '%'">0%</span>
            <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-subtle" role="progressbar" aria-valuemin="0" aria-valuemax="100" x-bind:aria-valuenow="percent" aria-label="{{ $heading ?? __('Progress') }}">
                <div class="h-full rounded-full bg-primary transition-[width] duration-300" x-bind:class="percent === 100 && 'bg-ok-fg!'" x-bind:style="{ width: percent + '%' }"></div>
            </div>
        </div>
    @endif

    <ul class="flex flex-col gap-1">{{ $slot }}</ul>

    @isset($footer)
        <div>{{ $footer }}</div>
    @endisset
</div>
