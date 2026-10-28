---
kind: component
group: Overlay
components: [danger-dialog]
---
# Danger dialog · `<x-ui::danger-dialog>`

For things that can’t be undone. The action only unlocks after the person types the resource name, as the design requires.

## Examples

### Type to confirm

resource is the text that must be typed; the red button stays disabled until it matches.

```blade
<x-ui::modal.trigger name="stop-watching">
    <x-ui::button variant="danger">Stop watching</x-ui::button>
</x-ui::modal.trigger>

<x-ui::danger-dialog
    name="stop-watching"
    heading="Stop watching sge-api?"
    text="Automatic review stops for this repository and the 2 draft reviews are discarded. PR history and lessons stay saved."
    resource="sge-api"
    confirm="Stop watching"
/>
```

### Without typing

Without resource it becomes a simple red confirmation, for low-impact destructive actions.

```blade
<x-ui::modal.trigger name="discard-draft">
    <x-ui::button icon="delete-02">Discard draft</x-ui::button>
</x-ui::modal.trigger>

<x-ui::danger-dialog
    name="discard-draft"
    icon="delete-02"
    heading="Discard the review draft?"
    text="The 3 unpublished comments will be deleted."
    confirm="Discard"
/>
```

## API

Attributes without the confirm: prefix go to the modal. After confirming, show a toast with "Undo" when possible. Default texts follow the app locale (English by default, Portuguese with pt_BR).

### `x-ui::danger-dialog`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | string | `null` | Name used by modal.trigger and by JS. |
| `heading` | string | `null` | The question, with the resource name. |
| `text` | string | `null` | What is lost and what stays saved. |
| `resource` | string | `null` | Text to type; without it there is no input. |
| `prompt` | string | `Type :resource to confirm` | Sentence above the input; :resource becomes the highlighted name. |
| `icon` | string | `alert-diamond` | Icon in the red square. |
| `confirm` | string | `Delete` | Destructive button text. |
| `cancel` | string | `Cancel` | Cancel button text. |
| `confirm:*` | attributes | `—` | Passed to the button: confirm:wire:click. |
| `close-on-confirm` | boolean | `true` | false keeps it open so the server closes it. |

## Accessibility

- The destructive button is truly disabled until the text matches.
- The input sets aria-invalid while the typed text doesn’t match.
- The typed text is cleared whenever the dialog closes.
- Never color alone: the title says what will happen and the icon reinforces it.
