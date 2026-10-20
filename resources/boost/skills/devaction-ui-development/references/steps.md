# Steps · `<x-ui::steps>`

Steps in a column joined by a line: a setup checklist or the history of a process.

## Examples

### Setup checklist

The line turns dark between done steps. The current step slot holds the action.

```blade
<x-ui::steps class="w-full max-w-sm">
    <x-ui::steps.item state="done" label="Account created" description="just now" />
    <x-ui::steps.item state="done" label="GitHub connected" description="6 repositories" />
    <x-ui::steps.item state="current" label="First project" description="SGE · Campo Limpo">
        <x-ui::button size="sm" variant="primary">Create project</x-ui::button>
    </x-ui::steps.item>
    <x-ui::steps.item label="Invite the team" description="0 of 2" />
    <x-ui::steps.item label="Contract with milestones" description="for due date reminders" />
</x-ui::steps>
```

### With error

state="error" for a step that failed.

```blade
<x-ui::steps class="w-full max-w-sm">
    <x-ui::steps.item state="done" label="Proposal sent" description="Oct 12" />
    <x-ui::steps.item state="error" label="Down payment" description="card declined · Oct 14" />
    <x-ui::steps.item label="Project kickoff" />
</x-ui::steps>
```

## API

For a flow where people move forward step by step (a wizard), use the horizontal Stepper.

### `x-ui::steps.item`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `null` | Step name. |
| `description` | string | `null` | Detail below. |
| `state` | done \| current \| error \| todo | `todo` | Step state. |

## Accessibility

- Ordered list; the current (or failed) step has aria-current="step".
- Done and error carry hidden text ("Done:", "Failed:", in the app locale) besides the icon.
