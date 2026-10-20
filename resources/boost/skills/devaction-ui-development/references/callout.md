# Callout · `<x-ui::callout>`

Highlights important information and guides to the next action. Amber always means "the AI did it", red means "someone is missing".

## Examples

### Variants

secondary for notes, success for decisions, warning and danger for urgency; ai and info come from the DevAction palette.

```blade
<div class="flex w-full max-w-xl flex-col gap-2.5">
    <x-ui::callout icon="information-circle" heading="Closed scope: this contract doesn’t accept new items without an amendment." />
    <x-ui::callout variant="success" icon="checkmark-circle-02" heading="M2 accepted on Sep 30 by the pastor’s email." />
    <x-ui::callout variant="warning" icon="alert-02" heading="2 days left until the end of the milestone." />
    <x-ui::callout variant="danger" icon="cancel-circle" heading="3 M3 items still have no PR." />
    <x-ui::callout variant="ai" icon="sparkles" heading="The AI summarized the client meeting." />
    <x-ui::callout variant="info" icon="idea-01" heading="Use DIA-123 in the PR title for automatic linking." />
</div>
```

### With actions

The actions slot holds the next steps. Decision variant: explain the impact and bring the action.

```blade
<x-ui::callout variant="danger" icon="alert-02" class="w-full max-w-xl">
    <x-ui::callout.heading>Why it needs you</x-ui::callout.heading>
    <x-ui::callout.text>
        PR #145 adds refunds, which are not in the M3 scope. Without an amendment, it becomes unbilled work.
        <x-ui::callout.link href="#">View the contract</x-ui::callout.link>
    </x-ui::callout.text>
    <x-slot:actions>
        <x-ui::button size="sm" icon="agreement-02">Draft amendment</x-ui::button>
        <x-ui::button size="sm" variant="ghost">Ignore</x-ui::button>
    </x-slot:actions>
</x-ui::callout>
```

### Inline actions and close

inline puts the actions on the right. The controls slot sits in the corner: closing is yours, the kit doesn’t decide where to store "dismissed".

```blade
<div class="flex w-full max-w-xl flex-col gap-2.5">
    <x-ui::callout variant="ai" icon="sparkles" heading="2 requests out of the M3 scope." inline>
        <x-slot:actions>
            <x-ui::button size="sm" variant="ai">Draft amendment</x-ui::button>
        </x-slot:actions>
    </x-ui::callout>
    <div x-data="{ visible: true }" x-show="visible">
        <x-ui::callout icon="notification-03" heading="Meeting with the church at 10am">
            <x-slot:controls>
                <x-ui::button variant="ghost" size="sm" icon="cancel-01" aria-label="Dismiss" x-on:click="visible = false" />
            </x-slot:controls>
        </x-ui::callout>
    </div>
</div>
```

### Labelled cards

stacked becomes a vertical card with the icon in a chip; label adds a mono label, like the callout grid in the design.

```blade
<div class="grid w-full grid-cols-1 gap-3.5 sm:grid-cols-3">
    <x-ui::callout stacked icon="information-circle" label="Note" heading="Closed scope" text="This contract doesn’t accept new items without an amendment." />
    <x-ui::callout stacked variant="ai" icon="sparkles" label="AI" heading="Meeting summary" text="The church asked for receipts over WhatsApp. Outside M3." />
    <x-ui::callout stacked variant="success" icon="checkmark-circle-02" label="Decision" heading="Approved by the client" text="M2 accepted on Sep 30 by the pastor’s email." />
</div>
```

### Icon in the heading

For a more compact layout, pass the icon to callout.heading instead of the callout.

```blade
<x-ui::callout class="w-full max-w-xl">
    <x-ui::callout.heading icon="file-edit">Terms updated</x-ui::callout.heading>
    <x-ui::callout.text>We updated the terms of use and the privacy policy.</x-ui::callout.text>
</x-ui::callout>
```

## API

Other HTML attributes (x-show, wire:key, id) go straight to the root element.

### `x-ui::callout`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | secondary \| success \| warning \| danger \| ai \| info | `secondary` | Maps to the status tokens. |
| `icon` | string \| slot | `null` | Hugeicons icon on the left; the slot accepts a custom SVG. |
| `heading` | string | `null` | Shortcut for callout.heading. |
| `text` | string | `null` | Shortcut for callout.text. |
| `inline` | boolean | `false` | Actions on the right, on the same line. |
| `stacked` | boolean | `false` | Vertical card with the icon in a chip. |
| `label` | string | `null` | Uppercase mono label above the title. |
| `actions` | slot | `null` | Buttons and links with the next steps. |
| `controls` | slot | `null` | Top-right corner, e.g. a close button. |

### `x-ui::callout.heading`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `icon` | string \| slot | `null` | Puts the icon inside the heading. |

### `x-ui::callout.link`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `href` | string | `—` | Link destination. |
| `external` | boolean | `false` | Opens in a new tab with rel="noopener noreferrer". |

## Accessibility

- Never color alone: the callout always has a title, and the icon reinforces it.
- Icons are decorative (aria-hidden); the text carries the information.
- An icon-only close button requires an aria-label.
- For messages that change in real time, use Alert, which has a status role.
