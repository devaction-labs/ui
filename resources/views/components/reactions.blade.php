{{--
    Reações com emoji: contagem por emoji, a sua marcada e um botão para reagir.

    <x-ui::reactions :reactions="['👍' => 2, '🎉' => 1]" :selected="['👍']" react="toggleReaction" />

    reactions: [emoji => contagem] (a contagem já inclui a sua reação)
    selected:  emojis em que você já reagiu
    react:     método do Livewire chamado com o emoji; sempre dispara reaction-toggled { emoji, selected }
    choices:   emojis do botão de reagir
--}}
@props([
    'reactions' => [],
    'selected' => [],
    'react' => null,
    'choices' => ['👍', '🎉', '❤️', '🚀', '👀', '😄'],
])

<div
    data-reactions
    x-data="{
        counts: @js((object) $reactions),
        mine: @js(array_values($selected)),
        picking: false,
        toggle(emoji) {
            const selected = ! this.mine.includes(emoji);
            this.mine = selected ? [...this.mine, emoji] : this.mine.filter((item) => item !== emoji);
            this.counts[emoji] = Math.max(0, (this.counts[emoji] ?? 0) + (selected ? 1 : -1));
            if (this.counts[emoji] === 0) delete this.counts[emoji];
            this.picking = false;
            this.$dispatch('reaction-toggled', { emoji, selected });
            @if ($react) this.$wire?.call(@js($react), emoji); @endif
        },
    }"
    x-on:keydown.escape="picking = false"
    x-on:click.outside="picking = false"
    {{ $attributes->class(['relative flex flex-wrap items-center gap-1.5']) }}
>
    <template x-for="(count, emoji) in counts" x-bind:key="emoji">
        <button
            type="button"
            x-on:click="toggle(emoji)"
            x-bind:aria-pressed="mine.includes(emoji).toString()"
            x-bind:aria-label="emoji + ' ' + count"
            class="inline-flex h-[26px] cursor-pointer items-center gap-1.5 rounded-full border border-line bg-card px-2.5 text-xs text-ink-2 transition hover:border-line-hover aria-pressed:border-info-line aria-pressed:bg-info-bg aria-pressed:text-info-fg"
        >
            <span x-text="emoji"></span><b class="font-semibold tabular-nums" x-text="count"></b>
        </button>
    </template>

    <button type="button" x-on:click="picking = ! picking" x-bind:aria-expanded="picking.toString()" aria-label="{{ __('Add reaction') }}" class="inline-flex size-[26px] cursor-pointer items-center justify-center rounded-full border border-line bg-card text-muted transition hover:border-line-hover hover:text-ink">
        <x-ui::icon name="add-01" class="size-3.5" />
    </button>

    <div x-show="picking" x-cloak x-transition.opacity class="absolute top-full left-0 z-20 mt-1.5 flex gap-0.5 rounded-xl border border-line bg-card p-1 shadow-[0_10px_30px_rgb(24_24_27/0.12)]" role="menu">
        @foreach ($choices as $choice)
            <button type="button" role="menuitem" x-on:click="toggle(@js($choice))" class="flex size-8 cursor-pointer items-center justify-center rounded-lg text-base transition hover:bg-subtle">{{ $choice }}</button>
        @endforeach
    </div>
</div>
