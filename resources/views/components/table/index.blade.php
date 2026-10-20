{{--
    Tabela de dados (API no estilo Flux), em <table> de verdade.

    <x-ui::table :paginator="$contacts" selectable wire:model.live="selected">
        <x-ui::table.columns>
            <x-ui::table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Nome</x-ui::table.column>
            <x-ui::table.column align="end">Valor</x-ui::table.column>
        </x-ui::table.columns>
        <x-ui::table.rows>
            @foreach ($contacts as $contact)
                <x-ui::table.row :key="$contact->id">
                    <x-ui::table.cell primary>{{ $contact->name }}</x-ui::table.cell>
                    <x-ui::table.cell align="end" label="Valor">…</x-ui::table.cell>
                </x-ui::table.row>
            @endforeach
        </x-ui::table.rows>
        <x-slot:bulk><x-ui::button size="sm">Arquivar</x-ui::button></x-slot:bulk>
    </x-ui::table>

    variant:    default (borda e cabeçalho cinza) · minimal (sem borda externa, padrão do shell)
    density:    default (linhas de 48 px) · compact (36 px)
    striped:    faixas alternadas
    selectable: caixa por linha e no cabeçalho; wire:model recebe as chaves marcadas; slot bulk vira a barra de ações
    expandable: reserva a coluna da seta para linhas com slot detail
    stack:      abaixo de 640 px cada linha vira um card (cell primary vira o título, label nomeia as outras)
    paginator:  mostra x-ui::pagination no rodapé (slot footer para outro conteúdo)
--}}
@props([
    'variant' => 'default',
    'density' => 'default',
    'striped' => false,
    'selectable' => false,
    'expandable' => false,
    'stack' => false,
    'paginator' => null,
    'livewire' => false,
])

<div
    data-table
    x-data="uiTable"
    x-modelable="selected"
    {{ $attributes->class([
        'relative w-full min-w-0',
        'overflow-hidden rounded-xl border border-line bg-card' => $variant !== 'minimal',
    ]) }}
>
    @isset($bulk)
        <div
            x-show="selected.length"
            x-cloak
            x-transition.opacity.duration.100ms
            @class([
                'absolute top-0 right-0 z-20 flex items-center gap-1.5 bg-ink px-2 text-[13px] text-on-ink',
                'left-11' => $selectable,
                'left-0' => ! $selectable,
                'h-11' => $density !== 'compact',
                'h-[34px]' => $density === 'compact',
            ])
        >
            <span class="mr-1.5 font-medium" x-text="selected.length + (selected.length === 1 ? ' selecionada' : ' selecionadas')"></span>
            {{ $bulk }}
            <button type="button" x-on:click="clearSelection()" class="ml-auto cursor-pointer text-[13px] text-on-ink/70 transition hover:text-on-ink">Limpar</button>
        </div>
    @endisset

    <div class="overflow-x-auto">
        <table @class([
            'w-full border-separate border-spacing-0 text-left text-sm text-ink',
            'max-sm:block' => $stack,
        ])>
            {{ $slot }}
        </table>
    </div>

    @if (isset($footer) || $paginator)
        <div @class([
            'flex flex-wrap items-center gap-3 px-3.5 py-2.5 text-[13px] text-ink-3',
            'border-t border-line' => $variant !== 'minimal',
        ])>
            {{ $footer ?? '' }}
            @if ($paginator)
                <x-ui::pagination :paginator="$paginator" :livewire="$livewire" class="flex-1" />
            @endif
        </div>
    @endif
</div>
