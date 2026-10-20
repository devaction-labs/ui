{{--
    Cabeçalho de página: breadcrumb, status, título de 32 px com linha de contexto, ações e abas.

    <x-ui::page-header heading="SGE · Gestão Eclesial" description="Igreja Batista de Campo Limpo · M3 vence sexta">
        <x-slot:breadcrumbs>…</x-slot:breadcrumbs>
        <x-slot:status><x-ui::tag color="ok" size="sm">Ativo</x-ui::tag></x-slot:status>
        <x-slot:actions>
            <x-ui::button>Exportar</x-ui::button>
            <x-ui::button variant="primary">Nova tarefa</x-ui::button>
        </x-slot:actions>
        <x-slot:tabs>…</x-slot:tabs>
    </x-ui::page-header>

    Ações: no máximo uma primária. size: base (32 px) · sm (24 px, páginas internas e drawers)
--}}
@props([
    'heading' => null,
    'description' => null,
    'size' => 'base',
])

<header data-page-header {{ $attributes->class(['flex flex-col gap-[18px]']) }}>
    @isset($breadcrumbs)
        <x-ui::breadcrumbs size="sm">{{ $breadcrumbs }}</x-ui::breadcrumbs>
    @endisset

    <div class="flex flex-wrap items-end justify-between gap-3.5">
        <div class="flex min-w-0 flex-col gap-1.5">
            @isset($status)
                <div class="flex flex-wrap items-center gap-2">{{ $status }}</div>
            @endisset
            @if ($heading)
                <h1 @class([
                    'font-semibold text-ink',
                    'text-[32px] leading-[1.1] tracking-[-0.03em]' => $size !== 'sm',
                    'text-2xl leading-tight tracking-[-0.02em]' => $size === 'sm',
                ])>{{ $heading }}</h1>
            @endif
            @if ($description)
                <p class="text-ink-3">{{ $description }}</p>
            @endif
            {{ $slot }}
        </div>

        @isset($actions)
            <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
        @endisset
    </div>

    @isset($tabs)
        <div class="-mt-1.5">{{ $tabs }}</div>
    @endisset
</header>
