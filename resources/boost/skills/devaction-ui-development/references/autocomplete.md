---
kind: component
group: Form
components: [autocomplete, autocomplete.group, autocomplete.item]
---
# Autocomplete · `<x-ui::autocomplete>`

Free text with suggestions as you type. Unlike the combobox, the value can be any text; suggestions only help to complete it.

## Examples

### Grouped suggestions

Type "ecto": groups with no match disappear and the matched part turns bold. The AI action always shows at the end.

```blade
<x-ui::autocomplete placeholder="Search lessons and PRs…" class="max-w-md" value="ecto">
    <x-ui::autocomplete.group heading="Lessons">
        <x-ui::autocomplete.item value="Ecto.Multi and rollback" badge="30" />
        <x-ui::autocomplete.item value="Nested Ecto changesets" badge="62" />
        <x-ui::autocomplete.item value="LiveView streams" badge="14" />
    </x-ui::autocomplete.group>
    <x-ui::autocomplete.group heading="Pull requests">
        <x-ui::autocomplete.item value="Donations with Ecto.Multi" prefix="#142" />
        <x-ui::autocomplete.item value="Receipt uploads" prefix="#139" />
    </x-ui::autocomplete.group>
    <div class="mx-1 my-1.5 h-px bg-line-soft"></div>
    <x-ui::autocomplete.item always keep variant="ai" label="Ask the AI" kbd="⌘↵" />
</x-ui::autocomplete>
```

### Simple list

No groups, with a label. Choosing fills the input; with wire:model.live the server receives the text.

```blade
<x-ui::autocomplete label="City" icon="building-03" placeholder="Start typing…" empty="No city suggested, you can type it anyway." class="max-w-md">
    <x-ui::autocomplete.item value="São Paulo" />
    <x-ui::autocomplete.item value="San Francisco" />
    <x-ui::autocomplete.item value="Santiago" />
    <x-ui::autocomplete.item value="Salvador" />
</x-ui::autocomplete>
```

### Server search

With :filter="false" items show exactly as the server sent them: the input’s wire:model.live sends the text and Livewire returns the results. keep + wire:click pick the record without replacing the text.

```blade
<x-ui::autocomplete wire:model.live.debounce.250ms="contactSearch" :filter="false" label="Contact" placeholder="Search by name or email…" empty="No contact found." class="max-w-md">
    <x-ui::autocomplete.item value="Ana Souza" icon="user" keep wire:click="pickContact(1)" />
    <x-ui::autocomplete.item value="Bruno Lima" icon="user" keep wire:click="pickContact(2)" />
</x-ui::autocomplete>
```

```php
public string $contactSearch = '';

#[Computed]
public function contactResults(): Collection
{
    return strlen(trim($this->contactSearch)) < 2
        ? collect()
        : Contact::search($this->contactSearch)->limit(6)->get();
}

public function pickContact(int $id): void
{
    $this->form['contact_id'] = $id;
    $this->contactSearch = Contact::find($id)->name;
}
```

## API

wire:model, name, value and placeholder go to the input. For server suggestions, render the items from a Livewire property updated with wire:model.live.debounce.

### `x-ui::autocomplete`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `filter` | boolean | `true` | false shows items exactly as the server sent them. |
| `label · description · error` | string | `null` | Like other fields. |
| `icon` | string | `search-01` | Icon on the left; null removes it. |
| `min-chars` | int | `1` | Characters that open the suggestions; 0 opens on focus. |
| `empty` | string | `null` | Text when no suggestion matches. |

### `x-ui::autocomplete.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `heading` | string | `null` | Uppercase mono title. |

### `x-ui::autocomplete.item`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `value · label` | string | `—` | Value sent to the input and text shown. |
| `icon · badge · prefix · kbd` | string | `null` | Suggestion details. |
| `always` | boolean | `false` | Always shown, like an action at the end. |
| `keep` | boolean | `false` | Does not replace the text; dispatches the select event. |
| `variant` | default \| ai | `default` | ai in amber with the AI dot. |

## Accessibility

- The input is role="combobox" with aria-autocomplete="list" and aria-expanded.
- ↑↓ move through suggestions, ↵ picks and Esc closes without clearing the text.
- Groups are role="group" with the title as aria-label.
- The bold highlight is visual only; the read text is the same as the suggestion.
