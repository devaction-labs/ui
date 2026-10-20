{{--
    Mensagem da conversa.

    <x-ui::chat.bubble from="user" time="14:02" status="sent">Resuma os achados do PR #214</x-ui::chat.bubble>
    <x-ui::chat.bubble from="ai" name="DevAction IA" time="14:02" copyable>
        Dois achados bloqueiam o M3…
        <x-slot:actions><x-ui::button size="xs" variant="ghost" icon="refresh">Refazer</x-ui::button></x-slot:actions>
    </x-ui::chat.bubble>
    <x-ui::chat.bubble from="ai" typing />

    from:     user (à direita, em tinta) · ai (à esquerda, avatar âmbar) · other (outra pessoa, à esquerda)
    status:   sending · sent · error (só para user)
    typing:   três pontos enquanto a resposta chega
    copyable: botão de copiar o texto (ai)
    feedback: botões 👍 👎, disparam chat-feedback com detail.value (up · down)
--}}
@props([
    'from' => 'user',
    'name' => null,
    'avatar' => null,
    'time' => null,
    'status' => null,
    'typing' => false,
    'copyable' => false,
    'feedback' => false,
])

@php
    $isUser = $from === 'user';
    $isAi = $from === 'ai';
    $name ??= $isAi ? 'IA' : null;
@endphp

<div
    data-chat-bubble
    data-from="{{ $from }}"
    @if ($copyable || $feedback) x-data="{ copied: false, vote: null }" @endif
    {{ $attributes->class([
        'flex max-w-full gap-2.5',
        'flex-row-reverse self-end' => $isUser,
        'self-start' => ! $isUser,
    ]) }}
>
    @unless ($isUser)
        @if ($isAi)
            <x-ui::avatar icon="sparkles" color="ai" size="sm" circle :aria-label="$name" class="mt-0.5 shrink-0" />
        @else
            <x-ui::avatar :name="$name" :src="$avatar" size="sm" color="auto" circle class="mt-0.5 shrink-0" />
        @endif
    @endunless

    <div @class(['flex min-w-0 flex-col gap-1', 'items-end' => $isUser, 'items-start' => ! $isUser])>
        @if (($name && ! $isUser) || $time)
            <div class="flex items-center gap-2 px-1 text-xs text-muted">
                @if ($name && ! $isUser)
                    <span class="font-medium text-ink-2">{{ $name }}</span>
                @endif
                @if ($time)
                    <time>{{ $time }}</time>
                @endif
            </div>
        @endif

        <div
            x-ref="content"
            @class([
                'max-w-[min(36rem,85vw)] text-[15px] leading-relaxed break-words',
                'rounded-[18px] rounded-tr-md bg-ink px-4 py-2.5 text-on-ink' => $isUser,
                'rounded-[18px] rounded-tl-md bg-wrap px-4 py-2.5 text-ink' => ! $isUser,
                'ring-1 ring-missing' => $isUser && $status === 'error',
                '[&_code]:rounded-[5px] [&_code]:bg-card [&_code]:px-1.5 [&_code]:font-mono [&_code]:text-[13px] [&_ol]:list-decimal [&_ol]:pl-5 [&_p+p]:mt-2 [&_strong]:font-semibold [&_ul]:list-disc [&_ul]:pl-5' => ! $isUser,
            ])
        >
            @if ($typing)
                <span class="flex h-[26px] items-center gap-1" role="status" aria-label="{{ __(':name is typing', ['name' => $name ?? __('Someone')]) }}">
                    @foreach ([0, 150, 300] as $delay)
                        <span class="size-1.5 rounded-full bg-muted motion-safe:animate-blink" style="animation-delay: {{ $delay }}ms"></span>
                    @endforeach
                </span>
            @else
                {{ $slot }}
            @endif
        </div>

        @isset($attachments)
            <div class="flex flex-wrap gap-2">{{ $attachments }}</div>
        @endisset

        @if ($isUser && $status)
            <span @class([
                'px-1 text-xs',
                'text-muted' => $status !== 'error',
                'text-risk-fg' => $status === 'error',
            ])>{{ match ($status) { 'sending' => __('Sending…'), 'error' => __('Not sent'), default => __('Sent') } }}</span>
        @endif

        @if (! $typing && ($copyable || $feedback || isset($actions)))
            <div class="flex items-center gap-0.5">
                @if ($copyable)
                    <button
                        type="button"
                        x-on:click="navigator.clipboard?.writeText($refs.content.innerText); copied = true; setTimeout(() => copied = false, 1600)"
                        x-bind:aria-label="copied ? @js(__('Copied')) : @js(__('Copy response'))"
                        aria-label="{{ __('Copy response') }}"
                        class="flex size-7 cursor-pointer items-center justify-center rounded-lg text-muted transition hover:bg-wrap hover:text-ink"
                    >
                        <x-ui::icon name="copy-01" class="size-3.5" x-show="! copied" />
                        <x-ui::icon name="tick-02" class="size-3.5 text-ok-fg" x-show="copied" x-cloak />
                    </button>
                @endif
                @if ($feedback)
                    @foreach (['up' => ['thumbs-up', __('Helpful response')], 'down' => ['thumbs-down', __('Bad response')]] as $vote => [$voteIcon, $voteLabel])
                        <button
                            type="button"
                            x-on:click="vote = vote === @js($vote) ? null : @js($vote); $dispatch('chat-feedback', { value: vote })"
                            x-bind:aria-pressed="vote === @js($vote)"
                            aria-label="{{ $voteLabel }}"
                            class="flex size-7 cursor-pointer items-center justify-center rounded-lg text-muted transition hover:bg-wrap hover:text-ink aria-pressed:text-ink"
                        >
                            <x-ui::icon :name="$voteIcon" class="size-3.5" />
                        </button>
                    @endforeach
                @endif
                {{ $actions ?? '' }}
            </div>
        @endif
    </div>
</div>
