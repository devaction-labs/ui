{{-- Campo de comentário de uma linha do diff (uso interno do x-ui::diff e do x-ui::diff.thread). --}}
@props([
    'line',
    'reply' => false,
])

<div class="flex flex-col gap-2" x-data="{ body: '' }">
    <label class="sr-only" for="diff-comment-{{ $line }}-{{ $reply ? 'reply' : 'new' }}">{{ $reply ? 'Responder' : 'Comentar na linha '.$line }}</label>
    <textarea
        id="diff-comment-{{ $line }}-{{ $reply ? 'reply' : 'new' }}"
        x-model="body"
        rows="2"
        placeholder="{{ $reply ? 'Responder…' : 'Comentar…' }} use @ para mencionar e ``` para código"
        x-on:keydown.meta.enter.prevent="send({{ $line }}, body); body = ''"
        x-on:keydown.ctrl.enter.prevent="send({{ $line }}, body); body = ''"
        class="min-h-14 w-full resize-y rounded-lg border border-line-strong bg-card px-2.5 py-2 text-sm text-ink outline-none placeholder:text-muted focus:border-ink focus:ring-4 focus:ring-ink/10"
    ></textarea>
    <div class="flex items-center gap-2">
        <span class="text-xs text-muted">Markdown suportado · ⌘↵ envia</span>
        <span class="ml-auto flex gap-2">{{ $slot }}</span>
        @unless ($reply)
            <x-ui::button size="sm" x-on:click="draftLine = null; body = ''">Cancelar</x-ui::button>
        @endunless
        <x-ui::button size="sm" variant="primary" x-on:click="send({{ $line }}, body); body = ''" x-bind:disabled="body.trim() === ''">Comentar</x-ui::button>
    </div>
</div>
