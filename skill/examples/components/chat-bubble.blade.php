{{-- Chat bubble: user and AI messages with status, typing indicator and actions, plus a two-person thread — see references/chat-bubble.md --}}
<x-ui::chat class="w-full max-w-2xl">
    <x-ui::chat.bubble from="user" time="14:02" status="sent">Summarize the blocking findings of PR #214 and tell me what’s left for M3 to close.</x-ui::chat.bubble>
    <x-ui::chat.bubble from="ai" name="DevAction AI" time="14:02" copyable feedback>
        <p>Two findings block M3:</p>
        <ul>
            <li>The donation transaction doesn’t roll back when the receipt fails (<code>Ecto.Multi</code>).</li>
            <li>The members list queries the database inside the loop.</li>
        </ul>
        <p>Fixing both, the milestone reaches <strong>26 of 26</strong> items done.</p>
        <x-slot:actions>
            <x-ui::button size="xs" variant="ghost" icon="refresh" aria-label="Retry">Retry</x-ui::button>
        </x-slot:actions>
    </x-ui::chat.bubble>
    <x-ui::chat.bubble from="user" time="14:03" status="sending">Open a task for each one?</x-ui::chat.bubble>
    <x-ui::chat.bubble from="ai" name="DevAction AI" typing />
</x-ui::chat>

{{-- Variation: two people, one message failed to send --}}
<x-ui::chat class="mt-8 w-full max-w-2xl">
    <x-ui::chat.bubble from="other" name="Pastor Marcos" time="09:12">Good morning! Can you deliver the treasurer report by Friday?</x-ui::chat.bubble>
    <x-ui::chat.bubble from="user" time="09:15" status="error">Yes, it goes out Thursday afternoon.</x-ui::chat.bubble>
</x-ui::chat>
