{{-- Item da navegação do x-ui::layout.topbar: <x-ui::layout.topbar.item href="/projetos" icon="folder-01" current>Projetos</x-ui::layout.topbar.item> --}}
@props([
    'href' => '#',
    'icon' => null,
    'current' => false,
])

<a
    href="{{ $href }}"
    @if ($current) aria-current="page" @endif
    {{ $attributes->class([
        'inline-flex h-9 shrink-0 items-center gap-2 rounded-[10px] px-3 text-sm whitespace-nowrap transition',
        'bg-wrap font-medium text-ink' => $current,
        'text-ink-3 hover:bg-wrap hover:text-ink' => ! $current,
    ]) }}
>
    @if ($icon)
        <x-ui::icon :name="$icon" class="size-4" />
    @endif
    {{ $slot }}
</a>
