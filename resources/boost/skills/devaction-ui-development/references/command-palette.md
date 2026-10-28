---
kind: component
group: Overlay
components: [command, command.empty, command.group, command.input, command.item, command.items]
---
# Command palette · `<x-ui::command-palette>`

⌘K search with groups and shortcuts. Filters as you type; ↑↓ navigate, ↵ picks and Esc closes.

## Examples

### In a modal with ⌘K

The standard: x-ui::command inside a bare modal, opened by the modal.trigger shortcut. Press ⌘K on this page.

```blade
<x-ui::modal.trigger name="command-palette" shortcut="cmd.k">
    <x-ui::button icon="search-01">Search <x-ui::kbd class="ml-1">⌘K</x-ui::kbd></x-ui::button>
</x-ui::modal.trigger>

<x-ui::modal name="command-palette" variant="bare" size="lg">
    <x-ui::command class="w-full">
        <x-ui::command.input placeholder="Search or type a command…" />
        <x-ui::command.items>
            <x-ui::command.group heading="Actions">
                <x-ui::command.item icon="git-pull-request" description="sge-api #214" kbd="↵">Open PR review</x-ui::command.item>
                <x-ui::command.item icon="sparkles" description="Requests a review now">Review with AI</x-ui::command.item>
                <x-ui::command.item icon="add-01" description="In SGE · M3" kbd="N">New task</x-ui::command.item>
            </x-ui::command.group>
            <x-ui::command.group heading="Projects">
                <x-ui::command.item icon="folder-01" keywords="church donation">SGE · Church Management</x-ui::command.item>
                <x-ui::command.item icon="folder-01">Hub API</x-ui::command.item>
                <x-ui::command.item icon="folder-01">AYRAM web</x-ui::command.item>
            </x-ui::command.group>
            <x-ui::command.empty>Nothing found. Try another term.</x-ui::command.empty>
        </x-ui::command.items>
    </x-ui::command>
</x-ui::modal>
```

### On the page

It also works outside the modal, as a quick search inside a panel.

```blade
<x-ui::command class="w-full max-w-lg">
    <x-ui::command.input placeholder="Filter contacts" :kbd="false" />
    <x-ui::command.items>
        <x-ui::command.item icon="user" description="Baptist Church · client" :close="false">Pastor Elias Moura</x-ui::command.item>
        <x-ui::command.item icon="user" description="Hub API · technical" :close="false">Carlos André</x-ui::command.item>
        <x-ui::command.item icon="user" description="AYRAM · finance" :close="false">Fernanda Paiva</x-ui::command.item>
        <x-ui::command.empty>No contacts.</x-ui::command.empty>
    </x-ui::command.items>
</x-ui::command>
```

## API

Each item accepts wire:click, x-on:click or href. The search ignores accents and looks at the text, description and keywords.

### `x-ui::command`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `default` | slot | `—` | Input, list, groups and empty state. |

### `x-ui::command.input`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `placeholder` | string | `null` | Input text; also becomes the label for screen readers. |
| `kbd` | string \| false | `Esc` | Key hint on the right. |

### `x-ui::command.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `heading` | string | `null` | Mono title; the group hides with no results. |

### `x-ui::command.item`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `icon` | string | `null` | Icon in the 28 px square. |
| `description` | string | `null` | Supporting line. |
| `kbd` | string | `null` | Shortcut on the right. |
| `keywords` | string | `null` | Extra search terms. |
| `href` | string | `null` | Becomes a link. |
| `close` | boolean | `true` | Closes the surrounding modal when chosen. |

### `x-ui::command.empty`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `default` | slot | `—` | Message when nothing matches the search. |

## Accessibility

- The input is role="combobox" with aria-activedescendant pointing to the active option.
- Options are role="option" with aria-selected, grouped in role="group".
- ↑↓ navigate, ↵ picks and Esc closes the modal; the mouse also activates the option.
- The empty state is role="status", announced when the search finds nothing.
