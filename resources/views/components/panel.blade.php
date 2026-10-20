{{--
    Painel: o contêiner dos artboards do design — moldura de 6 px e card com cabeçalho.
    Atalho sobre <x-ui::card wrap> com as partes já montadas.

    <x-ui::panel heading="Switch com rótulo" description="Mudanças salvam na hora.">
        <x-slot:actions><x-ui::button size="sm">Ver tudo</x-ui::button></x-slot:actions>
        …
        <x-slot:footer><x-ui::button variant="primary" size="sm">Salvar</x-ui::button></x-slot:footer>
    </x-ui::panel>

    <x-ui::panel heading="Tarefas" description=".data_table · ações fixas" mono flush>
        <table>…</table>
    </x-ui::panel>

    wrap:  moldura de 6 px (padrão true)
    mono:  descrição em mono, para a linha técnica do design
    flush: corpo de borda a borda, sem padding lateral
--}}
@props([
    'heading' => null,
    'description' => null,
    'size' => 'md',
    'wrap' => true,
    'mono' => false,
    'flush' => false,
    'actions' => null,
    'footer' => null,
])

@php
    $hasActions = $actions && $actions->isNotEmpty();
    $hasFooter = $footer && $footer->isNotEmpty();
@endphp

<x-ui::card :size="$size" :wrap="$wrap" :attributes="$attributes">
    @if ($heading || $description || $hasActions)
        <x-ui::card.header>
            @if ($heading)
                <x-ui::card.heading>{{ $heading }}</x-ui::card.heading>
            @endif
            @if ($hasActions)
                <x-ui::card.actions>{{ $actions }}</x-ui::card.actions>
            @endif
            @if ($description)
                <x-ui::card.subheading :mono="$mono">{{ $description }}</x-ui::card.subheading>
            @endif
        </x-ui::card.header>
    @endif

    <x-ui::card.body :flush="$flush">{{ $slot }}</x-ui::card.body>

    @if ($hasFooter)
        <x-ui::card.footer>{{ $footer }}</x-ui::card.footer>
    @endif
</x-ui::card>
