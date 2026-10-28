---
kind: component
group: Overlay
components: [confirm-dialog]
---
# Confirm dialog · `<x-ui::confirm-dialog>`

Asks for a yes before an action with effects outside the app, like publishing or sending. Icon, title, what will happen and two ways out.

## Examples

### Publish to GitHub

color="ai" for actions that take the AI’s work outside. confirm:wire:click runs the Livewire action.

```blade
<x-ui::modal.trigger name="confirm-publish">
    <x-ui::button variant="primary" icon="git-pull-request">Publish review</x-ui::button>
</x-ui::modal.trigger>

<x-ui::confirm-dialog
    name="confirm-publish"
    color="ai"
    icon="git-pull-request"
    heading="Publish the review to GitHub?"
    text="2 comments will go to PR #142 as DevAction AI. Carlos André gets notified right away."
    confirm="Publish"
/>
```

### Tones

The default icon follows the tone. success to confirm something good; info for neutral notices.

```blade
<x-ui::modal.trigger name="confirm-delivery">
    <x-ui::button icon="checkmark-circle-02">Mark M2 as delivered</x-ui::button>
</x-ui::modal.trigger>
<x-ui::modal.trigger name="confirm-invite">
    <x-ui::button icon="mail-01">Resend invite</x-ui::button>
</x-ui::modal.trigger>

<x-ui::confirm-dialog name="confirm-delivery" color="success" heading="Mark M2 as delivered?" text="The client gets notified and installment 3 is released for billing." confirm="Mark delivered" />
<x-ui::confirm-dialog name="confirm-invite" color="info" icon="mail-01" heading="Resend the invite?" text="Carlos André gets a new link valid for 7 days." confirm="Resend" cancel="Not now" />
```

## API

Attributes without the confirm: prefix go to the modal (wire:model, x-on:close…). For more content, use the default slot below the text. Default button texts follow the app locale (English by default, Portuguese with pt_BR).

### `x-ui::confirm-dialog`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | string | `null` | Name used by modal.trigger and by JS. |
| `heading` | string | `null` | The question, in one sentence. |
| `text` | string | `null` | What happens if confirmed. |
| `icon` | string | `per tone` | Icon in the square. |
| `color` | default \| ai \| success \| info | `default` | Icon tone. |
| `confirm` | string | `Confirm` | Confirm button text. |
| `cancel` | string | `Cancel` | Cancel button text. |
| `confirm-variant` | button variants | `primary` | Confirm button style. |
| `confirm:*` | attributes | `—` | Passed to the button: confirm:wire:click, confirm:x-on:click. |
| `close-on-confirm` | boolean | `true` | false keeps it open so the server closes it. |
| `size` | sm \| md \| lg | `sm` | Modal width. |

## Accessibility

- It is an x-ui::modal: focus is trapped, Esc cancels and focus returns to the opener.
- The title is an <h2> and comes before the text and actions.
- Cancel comes first in the DOM, so Tab doesn’t land straight on the action.
- No close button: both ways out are explicit in the footer.
