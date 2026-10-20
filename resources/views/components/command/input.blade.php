{{--
    Campo de busca da paleta: combobox ligado à lista de opções.

    <x-ui::command.input placeholder="Buscar ou digitar um comando…" />
    <x-ui::command.input placeholder="Buscar" :kbd="false" />
--}}
@props([
    'placeholder' => null,
    'kbd' => 'Esc',
    'label' => null,
])

<label class="flex h-14 shrink-0 items-center gap-3 border-b border-line-soft px-4">
    <x-ui::icon name="search-01" class="text-muted" />
    <span class="sr-only">{{ $label ?? $placeholder }}</span>
    <input
        type="text"
        role="combobox"
        aria-expanded="true"
        aria-autocomplete="list"
        autocomplete="off"
        spellcheck="false"
        x-model="query"
        x-bind:aria-activedescendant="activeId"
        x-on:keydown.arrow-down.prevent="move(1)"
        x-on:keydown.arrow-up.prevent="move(-1)"
        x-on:keydown.enter.prevent="choose()"
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        autofocus
        {{ $attributes->class(['min-w-0 flex-1 bg-transparent text-base text-ink outline-none placeholder:text-muted']) }}
    />
    @if ($kbd)
        <kbd class="rounded-md border border-b-2 border-line px-[7px] py-[3px] font-mono text-[11px] leading-none text-ink-3">{{ $kbd }}</kbd>
    @endif
</label>
