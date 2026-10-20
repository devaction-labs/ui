# Alert · `<x-ui::alert>`

Reports the result of something that just happened. Every alert has an icon and a title; the icon defaults per variant.

## Examples

### Variants

The default icon follows the variant. Amber is the AI working, blue is a tip, red is a failure.

```blade
<div class="flex w-full max-w-xl flex-col gap-2.5">
    <x-ui::alert variant="success" heading="GitHub connected" text="6 repositories synced." />
    <x-ui::alert variant="ai" heading="The AI is reviewing" text="Takes about 1 minute per PR." />
    <x-ui::alert variant="info" heading="Tip" text="Link the PR to the task with DIA-123 in the title." />
    <x-ui::alert variant="danger" heading="Webhook failed" text="Retrying in 30 s." />
</div>
```

### With an action

Actions and the controls slot work like in the Callout.

```blade
<x-ui::alert variant="danger" heading="Could not publish the review" class="w-full max-w-xl">
    GitHub rejected the token. Reconnect the integration to try again.
    <x-slot:actions>
        <x-ui::button size="sm" icon="github">Reconnect GitHub</x-ui::button>
    </x-slot:actions>
</x-ui::alert>
```

## API

Alert accepts the same props and slots as the Callout, except stacked and label.

### `x-ui::alert`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | secondary \| success \| warning \| danger \| ai \| info | `secondary` | danger uses role="alert"; the others, role="status". |
| `icon` | string | `per variant` | Overrides the default icon. |
| `heading` | string | `null` | Alert title. |
| `text` | string | `null` | Short text; the default slot accepts rich content. |
| `inline` | boolean | `false` | Actions on the right, on the same line. |
| `actions` | slot | `null` | Next steps. |
| `controls` | slot | `null` | Top-right corner, e.g. close. |

## Accessibility

- role="status" announces the message without interrupting the screen reader.
- danger uses role="alert" and is announced immediately.
- Icon and title are always present: color is never the only cue.
- Minimum 4.5:1 contrast in every variant, light and dark.
