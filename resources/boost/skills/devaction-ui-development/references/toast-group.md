---
kind: component
group: Overlay
components: [toast.group]
---
# Toast group · `<x-ui::toast-group>`

Short notices after an action. They stack: collapsed, the stack shows the front one with two peeking behind; on hover or focus it expands into a list and the timers pause.

## Examples

### Fire toasts

x-ui::toast.group is already in this page layout. Fire a few and hover the stack in the bottom right corner.

```blade
<x-ui::button variant="primary" x-on:click="$toast({ text: 'Changes saved.', variant: 'success' })">Saved</x-ui::button>
<x-ui::button x-on:click="$toast({ text: 'The AI finished reviewing #214.', variant: 'ai' })">AI</x-ui::button>
<x-ui::button x-on:click="$toast({ text: 'GitHub API is slow, retrying.', variant: 'warning' })">Warning</x-ui::button>
<x-ui::button x-on:click="$toast({ text: 'Failed to publish the review.', variant: 'danger' })">Error</x-ui::button>
```

### With action and heading

action shows a button; clicking it fires the event on window (Livewire listens with #[On]).

```blade
<x-ui::button x-on:click="$toast({ text: 'Task deleted.', action: { label: 'Undo', event: 'undo-delete' } })">With action</x-ui::button>
<x-ui::button x-on:click="$toast({ heading: 'Review published on GitHub', text: 'sge-api #142 · request changes', variant: 'success' })">With heading</x-ui::button>
```

### Loading until done

variant="loading" does not go away on its own. A new toast with the same id replaces it.

```blade
<x-ui::button
    x-on:click="
        $toast({ id: 'report-m3', text: 'Generating M3 report…', variant: 'loading' });
        setTimeout(() => $toast({ id: 'report-m3', text: 'M3 report ready.', variant: 'success' }), 2500);
    "
>Generate report</x-ui::button>
```

## API

In Livewire, use the DevAction\Ui\Livewire\InteractsWithToasts trait: $this->toast('Changes saved.', variant: 'success'). After a redirect, pass the session toasts to :toasts. Built-in labels (close button, region name) follow the app locale.

### `x-ui::toast.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `position` | bottom end \| bottom start \| bottom center \| top end \| top start \| top center | `bottom end` | Stack corner. |
| `toasts` | array | `[]` | Initial toasts, such as the session ones after a redirect. |
| `max` | int | `12` | Maximum number of toasts kept in the stack. |

### `toast (JS and Livewire)`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `text` | string | `—` | Message. |
| `heading` | string | `null` | Bold title above the text. |
| `variant` | default \| success \| ai \| warning \| danger \| loading | `default` | loading stays; danger uses role="alert". |
| `duration` | int | `5000` | Time in ms; 0 never goes away. |
| `id` | string | `automatic` | The same id replaces the toast. |
| `action` | { label, event, params } | `null` | Button that fires the event on window. |

### `JS`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `DevActionUI.toast({...})` | window | `—` | Fires a toast. |
| `$toast({...})` | Alpine | `—` | The same, inside Alpine. |
| `DevActionUI.dismissToast(id)` | window | `—` | Closes by id. |

## Accessibility

- Errors use role="alert" and are read immediately; the rest are a polite role="status".
- Alt+T moves focus to the stack; Esc closes the focused toast.
- With the mouse or focus on the stack, timers pause; they also pause when the tab is hidden.
- Swiping more than 80 px sideways dismisses the toast.
