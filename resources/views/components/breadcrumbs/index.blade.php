{{--
    Breadcrumbs (API no estilo Flux): onde a página fica. O último item, sem href, é a página atual.

    <x-ui::breadcrumbs>
        <x-ui::breadcrumbs.item href="/hoje">Hoje</x-ui::breadcrumbs.item>
        <x-ui::breadcrumbs.item href="/projetos/sge">SGE</x-ui::breadcrumbs.item>
        <x-ui::breadcrumbs.item>PR #142</x-ui::breadcrumbs.item>
    </x-ui::breadcrumbs>

    separator: slash (padrão) · chevron
    size:      sm (13 px, no topo da página) · base (14 px)
--}}
@props([
    'separator' => 'slash',
    'size' => 'base',
])

<nav aria-label="Breadcrumb" data-breadcrumbs {{ $attributes->class(['min-w-0']) }}>
    <ol @class([
        'flex min-w-0 flex-wrap items-center text-muted',
        'gap-2 text-[13px]' => $size === 'sm',
        'gap-2.5 text-sm' => $size !== 'sm',
    ])>
        {{ $slot }}
    </ol>
</nav>
