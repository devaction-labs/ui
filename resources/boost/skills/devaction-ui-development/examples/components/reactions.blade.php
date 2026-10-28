{{-- Reactions: emoji pills with counts under a comment — see references/reactions.md --}}
@php
    $comments = [
        [
            'author' => 'Ana Souza',
            'time' => '5 min ago',
            'body' => 'Order #1042 reviewed, ready to ship.',
        ],
        [
            'author' => 'Carlos Lima',
            'time' => '1 h ago',
            'body' => 'Stock count still differs by 2 units.',
        ],
    ];
@endphp

<div class="flex flex-col gap-4">
    {{-- Main usage: click a pill to toggle yours, the + button offers the default choices --}}
    <div class="flex flex-col gap-3 rounded-lg border border-line bg-card p-4 text-ink">
        <div class="flex items-center gap-2 text-sm">
            <span class="font-medium">{{ $comments[0]['author'] }}</span>
            <span class="text-muted">{{ $comments[0]['time'] }}</span>
        </div>
        <p class="text-sm">{{ $comments[0]['body'] }}</p>

        <x-ui::reactions
            :reactions="['👍' => 2, '🎉' => 1, '👀' => 1]"
            :selected="['👍']"
            react="toggleReaction"
        />
    </div>

    {{-- Variation: custom choices replace the emojis offered by the + button --}}
    <div class="flex flex-col gap-3 rounded-lg border border-line bg-card p-4 text-ink">
        <div class="flex items-center gap-2 text-sm">
            <span class="font-medium">{{ $comments[1]['author'] }}</span>
            <span class="text-muted">{{ $comments[1]['time'] }}</span>
        </div>
        <p class="text-sm">{{ $comments[1]['body'] }}</p>

        <x-ui::reactions
            :reactions="['✅' => 3]"
            :choices="['✅', '❌', '🤔', '🙏']"
        />
    </div>
</div>
