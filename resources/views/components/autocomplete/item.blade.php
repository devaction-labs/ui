{{--
    Sugestão do autocomplete. O texto vem de label (ou do value) e ganha o trecho digitado em negrito.

    <x-ui::autocomplete.item value="Ecto.Multi e rollback" badge="30" />
    <x-ui::autocomplete.item value="#142" label="Dízimos com Ecto.Multi" prefix="#142" />
    <x-ui::autocomplete.item always keep variant="ai" label="Perguntar à IA" kbd="⌘↵" />

    always:  aparece sempre, combine ou não com o texto (ação no fim da lista)
    keep:    escolher não troca o texto do campo; ouça o evento select (x-on:select / wire:click)
    variant: default · ai
--}}
@props([
    'value' => null,
    'label' => null,
    'icon' => null,
    'badge' => null,
    'prefix' => null,
    'kbd' => null,
    'always' => false,
    'keep' => false,
    'variant' => 'default',
])

@php
    $label ??= $value;
    $value ??= $label;
@endphp

<div
    role="option"
    data-autocomplete-item
    data-value="{{ $value }}"
    data-search="{{ trim($label.' '.$prefix) }}"
    @if ($always) data-autocomplete-always @endif
    @if ($keep) data-autocomplete-keep @endif
    x-show="matches($el)"
    x-on:mousedown.prevent
    x-on:click="choose($el)"
    x-bind:aria-selected="isActive($el)"
    x-bind:class="isActive($el) && 'bg-wrap'"
    {{ $attributes->class([
        'flex min-h-10 shrink-0 cursor-pointer items-center gap-2.5 rounded-[9px] px-2.5 text-sm',
        'text-ink' => $variant !== 'ai',
        'text-warn-fg' => $variant === 'ai',
    ]) }}
>
    @if ($variant === 'ai')
        <span class="size-2.5 shrink-0 rounded-full bg-ai" aria-hidden="true"></span>
    @endif
    @if ($icon)
        <x-ui::icon :name="$icon" class="size-4 shrink-0 text-ink-3" />
    @endif
    @if ($badge !== null)
        <span class="shrink-0 rounded-[5px] bg-warn-bg px-1.5 py-0.5 font-mono text-[11px] text-warn-fg">{{ $badge }}</span>
    @endif
    @if ($prefix)
        <span class="shrink-0 font-mono text-xs text-risk-fg">{{ $prefix }}</span>
    @endif
    <span class="min-w-0 flex-1 truncate" x-html="highlight(@js($label))">{{ $label }}</span>
    {{ $slot }}
    @if ($kbd)
        <kbd class="ml-auto shrink-0 font-mono text-[11px] text-muted">{{ $kbd }}</kbd>
    @endif
</div>
