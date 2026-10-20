{{--
    Conteúdo pronto de pessoa para o hover card: avatar, nome, cargo, presença e números.

    <x-ui::hover-card.person name="Alex" description="Dev · DevAction Labs" online :stats="['PRs abertos' => 4, 'na semana' => '32 h']" />
--}}
@props([
    'name',
    'description' => null,
    'src' => null,
    'online' => null,
    'stats' => [],
])

<span class="flex items-center gap-3">
    <x-ui::avatar :name="$name" :src="$src" color="ink" class="size-[42px]" />
    <span class="flex min-w-0 flex-col">
        <span class="truncate font-semibold">{{ $name }}</span>
        @if ($description)
            <span class="truncate text-[13px] text-muted">{{ $description }}</span>
        @endif
    </span>
    @if ($online !== null)
        <span @class(['ml-auto size-2 shrink-0 rounded-full', 'bg-ok-fg' => $online, 'bg-line-hover' => ! $online])></span>
        <span class="sr-only">{{ $online ? 'online' : 'offline' }}</span>
    @endif
</span>
@if ($stats !== [])
    <span class="mt-3 grid grid-cols-3 gap-2 text-center">
        @foreach ($stats as $statLabel => $statValue)
            <span class="rounded-lg bg-subtle p-2">
                <span class="block font-semibold">{{ $statValue }}</span>
                <span class="block text-[11px] text-muted">{{ $statLabel }}</span>
            </span>
        @endforeach
    </span>
@endif
