# Stepper · `<x-ui::stepper>`

Shows which step of a flow the person is on: done, current, failed and to do.

## Examples

### New contract

current="2": the first step is done and the second is current (amber bar). On mobile only the numbers show; the name stays available to screen readers.

```blade
<x-ui::stepper :current="2" class="w-full" :steps="[
    ['label' => 'Client', 'description' => 'who is hiring'],
    ['label' => 'Scope', 'description' => 'milestones and deliverables'],
    ['label' => 'Pricing', 'description' => 'installments and due dates'],
    ['label' => 'Review', 'description' => 'send for signature'],
]" />
```

### With error and content

error marks the current step in red. The slot goes below, for the step content and buttons.

```blade
<x-ui::stepper :current="3" error class="w-full" :steps="[
    ['label' => 'Client'],
    ['label' => 'Scope'],
    ['label' => 'Pricing'],
    ['label' => 'Review'],
]">
    <div class="mt-2 flex justify-between gap-2.5">
        <x-ui::button>Back</x-ui::button>
        <x-ui::button variant="primary">Try again</x-ui::button>
    </div>
</x-ui::stepper>
```

## API

Keep the step in a Livewire property and pass it to :current; the slot buttons call wire:click="next" and "previous". The counter ("Step 2 of 4") and status texts follow the app locale.

### `x-ui::stepper`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `steps` | array | `[]` | List of ['label' => …, 'description' => …]. |
| `current` | int | `1` | Current step, starting at 1; above the total, all are done. |
| `error` | boolean | `false` | Current step has an error. |
| `error-text` | string | `failed · try again` | Text for the failed step. |
| `counter` | boolean | `true` | "Step 2 of 4" on top. |

## Accessibility

- Ordered list; the current step has aria-current="step".
- Done steps carry a "Done:" text for screen readers, besides the check.
- State shows through color, icon and text, not color alone.
