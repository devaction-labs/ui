---
kind: component
group: AI
components: [ai-prompt]
---
# AI prompt · `<x-ui::ai-prompt>`

Input for chatting with the AI: grows up to 8 lines, accepts attachments, / commands and @ mentions, and turns into a stop button while the AI responds.

## Examples

### Composer

Enter sends, Shift+Enter adds a line break. Send is disabled with no text or over the limit; the counter appears as you type.

```blade
<x-ui::ai-prompt max="4000" class="max-w-xl" x-on:ai-prompt-submit="$toast({ text: 'Sent: ' + $event.detail.text, variant: 'ai' })">
    <x-slot:toolbar>
        <x-ui::badge color="warn" icon="sparkles">Reviewer · strict</x-ui::badge>
    </x-slot:toolbar>
</x-ui::ai-prompt>
```

### With attachments, while responding

The attachments slot shows files above the text. With busy, the text locks and send becomes stop (Esc stops too).

```blade
<x-ui::ai-prompt busy class="max-w-xl">
    <x-slot:attachments>
        <span class="flex h-[52px] items-center gap-2 rounded-xl border border-line bg-subtle p-[5px] pr-3 text-[13px]">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-risk-bg font-mono text-[10px] font-semibold text-risk-fg">PDF</span>
            <span class="flex flex-col"><span class="font-medium text-ink">Amendment 02.pdf</span><span class="text-[11px] text-muted">1.8 MB</span></span>
        </span>
    </x-slot:attachments>
</x-ui::ai-prompt>
```

## API

With Livewire: wire:model="prompt" submit="send" stop="cancel" busy-property="isResponding". The attach button dispatches ai-prompt-attach so you can open your own file picker. The kit’s own texts follow the app locale (English by default, Portuguese with pt_BR).

### `x-ui::ai-prompt`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `placeholder · label` | string | `Ask about the project… · Message to the AI` | Input text and accessible name. |
| `max` | int | `null` | Character limit with a counter. |
| `submit · stop` | string | `null` | Livewire methods called on send and stop. |
| `busy · busy-property` | boolean · string | `false · null` | AI responding (fixed value or Livewire property). |
| `attach · commands · mentions` | boolean | `true` | Shows the attach, / and @ buttons. |
| `slots` | attachments · toolbar | `—` | Attachments on top and extra items in the bar. |
| `events` | ai-prompt-submit · ai-prompt-stop · ai-prompt-attach | `—` | For use without Livewire. |

## Accessibility

- textarea with aria-label; over the limit it gets aria-invalid and the counter is announced.
- The send button is renamed "Stop response" while the AI responds.
- Enter does not send during accent composition (IME).
