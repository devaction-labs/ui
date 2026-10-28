---
kind: component
group: Data
components: [checklist, checklist.item]
---
# Checklist · `<x-ui::checklist>`

A list of steps to tick off, with a progress bar and the option to hide what is already done.

## Examples

### Ready to merge

With a heading come the percentage, the progress bar (green at 100%) and the button that hides checked items. description shows who owns each step.

```blade
<x-ui::checklist heading="Ready to merge" class="w-full max-w-md">
    <x-ui::checklist.item checked description="Carlos">Post and settle in the same transaction</x-ui::checklist.item>
    <x-ui::checklist.item checked description="Carlos">Roll back if the receipt fails</x-ui::checklist.item>
    <x-ui::checklist.item description="Carlos">Generate the receipt after the commit</x-ui::checklist.item>
    <x-ui::checklist.item description="Alex">Test a failure halfway through</x-ui::checklist.item>
    <x-slot:footer>
        <x-ui::button size="sm" variant="ghost" icon="add-01">Add an item</x-ui::button>
    </x-slot:footer>
</x-ui::checklist>
```

### Inside a comment

Without a heading the checklist is only the items, to sit inside a comment or a card description.

```blade
<x-ui::checklist class="w-full max-w-sm">
    <x-ui::checklist.item checked>Lock the cash row while posting</x-ui::checklist.item>
    <x-ui::checklist.item>Send the receipt by email</x-ui::checklist.item>
</x-ui::checklist>
```

### With Livewire

wire:model goes to the native checkbox of each item. Bind an array of booleans and save it when it changes.

```blade
<x-ui::checklist heading="Release checklist" class="w-full max-w-md">
    <x-ui::checklist.item wire:model.live="checks.migrations">Migrations reviewed</x-ui::checklist.item>
    <x-ui::checklist.item wire:model.live="checks.queues">Queues drained</x-ui::checklist.item>
    <x-ui::checklist.item wire:model.live="checks.changelog">Changelog updated</x-ui::checklist.item>
</x-ui::checklist>
```

```php
/** @var array<string, bool> */
public array $checks = ['migrations' => true, 'queues' => false, 'changelog' => false];

public function updatedChecks(): void
{
    $this->release->update(['checks' => $this->checks]);
}
```

## API

### `x-ui::checklist`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `heading` | string | `null` | Title; brings the hide-checked button and the progress bar. |
| `progress` | boolean | `true with heading` | Shows the percentage and the bar. |
| `actions` | slot | `—` | Buttons next to the title. |
| `footer` | slot | `—` | Below the items (add an item). |

### `x-ui::checklist.item`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `checked` | boolean | `false` | Starts checked (without wire:model). |
| `description` | string | `null` | Smaller text on the right: owner, due date. |
| `wire:model` | attribute | `—` | Forwarded to the native checkbox. |

## Accessibility

- Each item is a native checkbox inside its <label>: Space toggles it and screen readers announce the state.
- The bar is a progressbar with aria-valuenow; the percentage is also written as text.
- Checked items are struck through and dimmed, and the checkbox state carries the meaning.
