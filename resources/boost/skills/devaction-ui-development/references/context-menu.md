# Context menu · `<x-ui::context-menu>`

Actions for a row or card via right-click (or Shift+F10), with the same items as the ⋯ menu.

## Examples

### In a task list

Right-click a row, or focus a row with Tab and press Shift+F10. The menu opens at the click point and uses x-ui::menu: arrows, first letter, Enter and Esc.

```blade
<x-ui::context-menu class="w-full max-w-lg overflow-hidden rounded-xl border border-line text-sm">
    @foreach (['Fix the financial report', 'Migrate old milestones with due_on', 'Review treasurer permissions'] as $task)
        <div tabindex="0" class="flex h-11 items-center gap-2.5 border-b border-line-soft px-3.5 outline-none last:border-b-0 focus:bg-wrap focus:outline-2 focus:-outline-offset-2 focus:outline-ink">
            <span class="size-4 rounded border border-line-hover"></span>{{ $task }}
        </div>
    @endforeach

    <x-ui::menu>
        <x-ui::menu.item icon="view" kbd="↵">Open</x-ui::menu.item>
        <x-ui::menu.item icon="git-pull-request">View PR #214</x-ui::menu.item>
        <x-ui::menu.item icon="sparkles" kbd="⌘R">Review with AI</x-ui::menu.item>
        <x-ui::menu.separator />
        <x-ui::menu.item icon="user-add-01" kbd="A">Assign to…</x-ui::menu.item>
        <x-ui::menu.item icon="calendar-01" kbd="D">Change due date</x-ui::menu.item>
        <x-ui::menu.item icon="copy-01" kbd="⌘C">Copy link</x-ui::menu.item>
        <x-ui::menu.separator />
        <x-ui::menu.item icon="delete-02" variant="danger" kbd="⌫">Delete task</x-ui::menu.item>
    </x-ui::menu>
</x-ui::context-menu>
```

## API

The menu is the same x-ui::menu as the dropdown (items, groups, radios and submenus). To know which row was clicked, listen to context-menu-open: detail.target is the element.

### `x-ui::context-menu`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `slot` | slot | `—` | The area that receives the right-click, plus an x-ui::menu. |
| `context-menu-open` | event | `—` | Dispatched on open; detail.target is the clicked row or element. |

## Accessibility

- Shift+F10 and the Menu key open it from the keyboard, on the focused element.
- Esc closes and returns focus to the row; the menu has role="menu".
- Offer the same actions in a visible ⋯ button: right-click doesn’t exist on mobile.
