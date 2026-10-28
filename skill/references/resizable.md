---
kind: component
group: Navigation
components: [resizable, resizable.handle, resizable.panel]
---
# Resizable · `<x-ui::resizable>`

Two panels with a divider that changes their size: diff and AI summary, list and detail.

## Examples

### Diff and summary

Drag the divider, use ← → while it is focused (Shift moves 10%) or double-click to go back to 58%. persist remembers the size in the browser.

```blade
<x-ui::resizable default="58" min="30" max="75" persist="docs-pr-review" class="h-[300px] w-full">
    <x-ui::resizable.panel>
        <div class="font-mono text-[11px] text-muted">DIFF</div>
        <pre class="mt-2.5 font-mono text-xs leading-[1.9] text-ink-2"><span class="block bg-risk-bg text-risk-fg">-  'due_on' => ['nullable', 'date'],</span><span class="block bg-ok-bg text-ok-fg">+  'due_on' => ['required', 'date'],</span>   'amount' => ['required', 'integer'],</pre>
    </x-ui::resizable.panel>
    <x-ui::resizable.handle label="Diff size" />
    <x-ui::resizable.panel class="bg-ai-soft">
        <div class="font-mono text-[11px] text-accent">AI SUMMARY</div>
        <p class="mt-2.5 text-sm leading-[1.6] text-ink-2">The PR makes due_on required. Two blocking findings: a migration for old milestones is missing and the test does not cover a milestone without a date.</p>
    </x-ui::resizable.panel>
</x-ui::resizable>
```

### Vertical

vertical stacks the panels; the divider moves with ↑ ↓.

```blade
<x-ui::resizable vertical default="40" class="h-[260px] w-full max-w-md">
    <x-ui::resizable.panel class="text-sm">Editor</x-ui::resizable.panel>
    <x-ui::resizable.handle label="Editor height" />
    <x-ui::resizable.panel class="text-sm text-ink-3">Preview</x-ui::resizable.panel>
</x-ui::resizable>
```

## API

### `x-ui::resizable`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `default` | number | `50` | Initial size of the first panel, in %. |
| `min · max` | number | `20 · 80` | Limits in %. |
| `vertical` | boolean | `false` | Panels stacked on top of each other. |
| `persist` | string | `null` | Key to remember the size (localStorage). |
| `resized` | event | `—` | Fired on release, with detail.size. |

### `x-ui::resizable.handle`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `Resize panels` | Name read by screen readers (translated by the app locale). |

## Accessibility

- The divider is a focusable role="separator" with aria-valuenow, aria-valuemin and aria-valuemax.
- ← → (↑ ↓ when vertical) change 5%, Shift changes 10%, Home and End go to the limits and Enter resets.
- Works with mouse, touch and pen (pointer events).
