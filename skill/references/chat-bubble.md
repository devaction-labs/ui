---
kind: component
group: AI
components: [chat, chat.bubble]
---
# Chat bubble · `<x-ui::chat-bubble>`

Messages in a conversation with the AI or another person: sender, time, status, typing, copy and rate the answer.

## Examples

### Conversation with the AI

The person’s message sits on the right, in ink; the AI’s on the left, with the amber avatar. copyable and feedback appear under the answer.

```blade
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
            <x-ui::button size="xs" variant="ghost" icon="refresh">Retry</x-ui::button>
        </x-slot:actions>
    </x-ui::chat.bubble>
    <x-ui::chat.bubble from="user" time="14:03" status="sending">Open a task for each one?</x-ui::chat.bubble>
    <x-ui::chat.bubble from="ai" name="DevAction AI" typing />
</x-ui::chat>
```

### With another person and a send error

from="other" uses the person’s avatar. status="error" marks a message that wasn’t sent.

```blade
<x-ui::chat class="w-full max-w-2xl">
    <x-ui::chat.bubble from="other" name="Pastor Marcos" time="09:12">Good morning! Can you deliver the treasurer report by Friday?</x-ui::chat.bubble>
    <x-ui::chat.bubble from="user" time="09:15" status="error">Yes, it goes out Thursday afternoon.</x-ui::chat.bubble>
</x-ui::chat>
```

## API

The AI answer content accepts simple HTML (p, ul, ol, code, strong). Sanitize what comes from the model on the server before rendering it unescaped.

### `x-ui::chat`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `Conversation` | aria-label of the log. |

### `x-ui::chat.bubble`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `from` | user \| ai \| other | `user` | Who sent it. |
| `name · avatar · time` | string | `null` | Name, photo and time. |
| `status` | sending \| sent \| error | `null` | Status of the person’s message. |
| `typing` | boolean | `false` | Three dots while the answer arrives. |
| `copyable · feedback` | boolean | `false` | Copy and rate (dispatches chat-feedback). |
| `slots` | actions · attachments | `—` | Extra actions and attachments under the message. |

## Accessibility

- The conversation is role="log" with aria-live="polite": new messages are announced without interrupting.
- Typing has role="status" with a text saying the AI is typing.
- The copy and rate buttons have aria-labels; rating exposes aria-pressed.
- The typing dots respect prefers-reduced-motion.
