# Progress toast · `<x-ui::progress-toast>`

A toast for long background tasks: the background fills as it advances and, when complete, shows a check and goes away.

## Examples

### Floating, in the stack

DevActionUI.progressToast(id, {...}) creates and advances the same toast. At 100 it turns into success and dismisses itself.

```blade
<x-ui::button
    variant="primary"
    icon="upload-04"
    x-on:click="
        let value = 0;
        const timer = setInterval(() => {
            value = Math.min(100, value + 12);
            DevActionUI.progressToast('import-contacts', { value, heading: 'Importing contacts', text: Math.round(value * 31) + ' of 3,100' });
            if (value === 100) clearInterval(timer);
        }, 400);
    "
>Import contacts</x-ui::button>
```

### In the page

x-ui::toast.progress shows the same look inside the page, for example above a list that is being imported.

```blade
<div class="flex w-full max-w-md flex-col gap-3">
    <x-ui::toast.progress :value="64" heading="Syncing repositories" text="4 of 6 · sge-api" />
    <x-ui::toast.progress :value="100" heading="M3 report ready" text="12-page PDF" />
</div>
```

## API

In Livewire: $this->progressToast('import', $percent, 'Importing contacts…') from the InteractsWithToasts trait. Call it again with the same id to advance.

### `DevActionUI.progressToast(id, options)`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `value` | int | `0` | Percentage from 0 to 100. |
| `heading` | string | `null` | Bold title. |
| `text` | string | `—` | Detail below the title. |
| `dismissOnComplete` | boolean | `true` | false keeps the toast after 100. |

### `x-ui::toast.progress`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `value` | int | `0` | Percentage from 0 to 100. |
| `heading` | string | `null` | Title; becomes the bar aria-label. |
| `text` | string | `null` | Detail below the title. |

## Accessibility

- role="status" with a polite aria-live: progress does not interrupt the screen reader.
- The percentage is a progressbar with aria-valuenow.
- On completion, icon and color change together: the check confirms without relying on green alone.
- The fill respects prefers-reduced-motion.
