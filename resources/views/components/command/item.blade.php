{{--
    Opção da paleta. Escolher fecha o modal em volta (desligue com :close="false").

    <x-ui::command.item icon="git-pull-request" description="sge-api #214" kbd="↵" wire:click="openReview(214)">
        Abrir review do PR
    </x-ui::command.item>
    <x-ui::command.item icon="folder-01" href="/projetos/sge" keywords="igreja dízimo">SGE · Gestão Eclesial</x-ui::command.item>

    keywords: termos extras para a busca, além do texto e da descrição
--}}
@props([
    'icon' => null,
    'description' => null,
    'kbd' => null,
    'keywords' => null,
    'href' => null,
    'close' => true,
    'disabled' => false,
])

@php
    $search = trim(strip_tags((string) $slot).' '.$description.' '.$keywords);
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="button" @endif
    role="option"
    data-command-item
    data-search="{{ $search }}"
    x-id="['ui-command-item']"
    x-bind:id="$id('ui-command-item')"
    x-show="isVisible($el)"
    x-bind:aria-selected="(activeId === $el.id).toString()"
    x-on:mouseenter="activeId = $el.id"
    @if ($close) x-on:click="$dispatch('ui-modal-close-self')" @endif
    @if (! $href) @disabled($disabled) @endif
    tabindex="-1"
    {{ $attributes->class([
        'group/command flex min-h-11 w-full shrink-0 cursor-pointer items-center gap-3 rounded-[10px] px-2.5 py-1.5 text-left text-sm text-ink outline-none',
        'aria-selected:bg-wrap disabled:cursor-not-allowed disabled:opacity-45',
    ]) }}
>
    @if ($icon)
        <span class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-wrap text-ink-2 group-aria-selected/command:bg-card">
            <x-ui::icon :name="$icon" class="size-4" />
        </span>
    @endif
    <span class="flex min-w-0 flex-1 flex-col">
        <span class="truncate">{{ $slot }}</span>
        @if ($description)
            <span class="truncate text-xs text-muted">{{ $description }}</span>
        @endif
    </span>
    @if ($kbd)
        <kbd class="rounded-md border border-b-2 border-line px-1.5 py-0.5 font-mono text-[11px] leading-none text-ink-3">{{ $kbd }}</kbd>
    @endif
</{{ $tag }}>
