{{--
    Linha de estado vazio da tabela (ocupa todas as colunas).

    <x-ui::table.empty icon="file-01" heading="Nenhum contrato com esse filtro" text="Tente outro status.">
        <x-ui::button size="sm">Limpar filtros</x-ui::button>
    </x-ui::table.empty>
--}}
@props([
    'icon' => 'search-01',
    'heading' => null,
    'text' => null,
])

@php
    $heading ??= __('Nothing here');
@endphp

<tr data-table-empty>
    <td colspan="100" {{ $attributes->class(['px-5 py-10']) }}>
        <div class="flex flex-col items-center text-center">
            <span class="flex size-11 items-center justify-center rounded-xl bg-wrap text-ink-3">
                <x-ui::icon :name="$icon" class="size-5" />
            </span>
            <div class="mt-3 font-semibold text-ink">{{ $heading }}</div>
            @if ($text)
                <div class="mt-1 text-[13px] text-muted">{{ $text }}</div>
            @endif
            @if ($slot->isNotEmpty())
                <div class="mt-3.5 flex flex-wrap justify-center gap-2">{{ $slot }}</div>
            @endif
        </div>
    </td>
</tr>
