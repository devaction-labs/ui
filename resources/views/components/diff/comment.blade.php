{{--
    Comentário de uma conversa do diff, com sugestão de mudança opcional.

    <x-ui::diff.comment author="Revisor DevAction" ai when="agora">due_on virou obrigatório…</x-ui::diff.comment>
    <x-ui::diff.comment author="Alex" when="há 2 min" suggestion-from="    validate_required(…)" suggestion-to="    validate_required(… -- [:due_on])" apply="applySuggestion(3)">Pode sugerir?</x-ui::diff.comment>

    apply: expressão do Livewire do "Aplicar sugestão" (vira commit, por exemplo)
--}}
@props([
    'author',
    'when' => null,
    'ai' => false,
    'avatar' => null,
    'suggestionFrom' => null,
    'suggestionTo' => null,
    'apply' => null,
])

<div data-diff-comment class="flex gap-2.5 border-b border-line-soft px-3.5 py-3">
    @if ($ai)
        <x-ui::avatar icon="sparkles" color="ai" size="xs" class="size-[26px]" aria-label="IA" />
    @else
        <x-ui::avatar :name="$author" :src="$avatar" color="ink" size="xs" class="size-[26px]" />
    @endif
    <div class="min-w-0 flex-1">
        <div class="flex items-center gap-2 text-[13px]">
            <span class="font-semibold">{{ $author }}</span>
            @if ($ai)
                <span class="inline-flex h-[18px] items-center rounded-[5px] border border-warn-line bg-warn-bg px-1.5 font-mono text-[10px] text-warn-fg">IA</span>
            @endif
            @if ($when)
                <span class="text-muted">{{ $when }}</span>
            @endif
        </div>
        <div class="mt-1 text-ink-2">{{ $slot }}</div>
        @if ($suggestionFrom !== null || $suggestionTo !== null)
            <div class="mt-2 overflow-hidden rounded-lg border border-line font-mono text-xs">
                <div class="flex items-center bg-subtle px-2.5 py-1.5 font-sans text-xs text-ink-3">
                    Sugestão de mudança
                    <button type="button" @if ($apply) wire:click="{{ $apply }}" @endif x-on:click="$dispatch('diff-apply-suggestion', { from: @js($suggestionFrom), to: @js($suggestionTo) })" class="ml-auto cursor-pointer font-medium text-ink hover:underline">Aplicar sugestão</button>
                </div>
                @if ($suggestionFrom !== null)
                    <div class="bg-risk-bg px-2.5 py-1.5 whitespace-pre text-risk-fg">− {{ $suggestionFrom }}</div>
                @endif
                @if ($suggestionTo !== null)
                    <div class="bg-ok-bg px-2.5 py-1.5 whitespace-pre text-ok-fg">+ {{ $suggestionTo }}</div>
                @endif
            </div>
        @endif
    </div>
</div>
