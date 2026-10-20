# Pillbox · `<x-ui::pillbox>`

Several values as pills inside a field: pick from a list (with local or server search) or type free text, like tags and emails.

## Examples

### With options

With x-ui::pillbox.option the pillbox becomes a multiselect: click to open the list and pick several. searchable adds a search inside the list.

```blade
<x-ui::pillbox label="Tags" placeholder="Pick the tags" searchable search-placeholder="Search tags…" :value="['vip']" class="max-w-md">
    <x-ui::pillbox.option value="vip" icon="star">VIP</x-ui::pillbox.option>
    <x-ui::pillbox.option value="referral" icon="user-add-01">Referral</x-ui::pillbox.option>
    <x-ui::pillbox.option value="partner" icon="handshake">Partner</x-ui::pillbox.option>
    <x-ui::pillbox.option value="overdue" icon="alert-02">Overdue</x-ui::pillbox.option>
</x-ui::pillbox>
```

### Combobox

variant="combobox" lets you type straight into the field to filter; ⌫ in the empty field removes the last pill.

```blade
<x-ui::pillbox variant="combobox" label="Owners" placeholder="Type a name…" :value="['ana']" class="max-w-md">
    <x-ui::pillbox.option value="ana" selected-label="Ana">Ana Souza · Sales</x-ui::pillbox.option>
    <x-ui::pillbox.option value="bruno" selected-label="Bruno">Bruno Lima · Sales</x-ui::pillbox.option>
    <x-ui::pillbox.option value="carla" selected-label="Carla">Carla Dias · Customer success</x-ui::pillbox.option>
    <x-ui::pillbox.option value="diego" selected-label="Diego">Diego Rocha · Finance</x-ui::pillbox.option>
</x-ui::pillbox>
```

### Server search and create option

:filter="false" leaves filtering to Livewire: the input slot carries the text and the server returns the options. pillbox.option.create shows up after min-length characters and calls its wire:click.

```blade
<x-ui::pillbox wire:model.live="selectedTags" variant="combobox" :filter="false" label="Tags" class="max-w-md">
    <x-slot:input>
        <x-ui::pillbox.input wire:model.live.debounce.250ms="search" placeholder="Pick the tags…" />
    </x-slot:input>
    <x-ui::pillbox.option value="1">VIP</x-ui::pillbox.option>
    <x-ui::pillbox.option value="2">Referral</x-ui::pillbox.option>
    <x-ui::pillbox.option.create wire:click="createTag" min-length="2">
        Create "<span wire:text="search"></span>"
    </x-ui::pillbox.option.create>
</x-ui::pillbox>
```

```php
public array $selectedTags = [];

public string $search = '';

#[Computed]
public function tags(): Collection
{
    return Tag::query()
        ->where('name', 'like', '%'.trim($this->search).'%')
        ->orWhereIn('id', $this->selectedTags)
        ->orderBy('name')
        ->limit(20)
        ->get();
}

public function createTag(): void
{
    $tag = Tag::create(['name' => trim($this->search)]);

    $this->selectedTags[] = (string) $tag->id;
    $this->search = '';
}
```

### Free text

Without options, each typed text becomes a pill. ↵ or comma adds, ⌫ in the empty field removes the last one and × removes a pill. Duplicates are ignored.

```blade
<x-ui::pillbox label="Project stack" :value="['Elixir', 'Phoenix', 'LiveView']" class="max-w-md" />
```

### With suggestions and a limit

suggestions show up filtered while you type (↑↓ and ↵ pick). max locks the field once it hits the limit.

```blade
<x-ui::pillbox
    label="Contact tags"
    placeholder="Add a tag…"
    :value="['Customer', 'Referral']"
    :suggestions="['Customer', 'Hot lead', 'Cold lead', 'Referral', 'Partner', 'Overdue', 'VIP']"
    max="5"
    description="Up to 5 tags."
    class="max-w-md"
/>
```

## API

wire:model receives an array (option values, or the texts in free mode). With name, each pill becomes a hidden name[] input for regular forms. With server search, keep the already picked options in the list. Default texts follow the app locale.

### `x-ui::pillbox`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description · error` | string | `null` | Same as the other fields; name.* errors show up too. |
| `value` | array | `[]` | Initial pills. |
| `suggestions` | array | `[]` | Suggestions filtered while typing. |
| `placeholder` | string | `Add…` | Field text. |
| `max` | int | `null` | Pill limit. |
| `unique` | boolean | `true` | Ignores duplicates (accent and case insensitive). |
| `name` | string | `null` | Sends name[] in regular forms. |
| `variant` | string | `default` | With options: default (opens the list) · combobox (type in the field). |
| `searchable · search-placeholder` | boolean · string | `false · Search…` | Search inside the list. |
| `filter` | boolean | `true` | false leaves filtering to the server. |
| `size` | string | `base` | base · sm. |
| `slots` | input · search · empty | `—` | Combobox field, list search and empty list, to bind to Livewire. |

### `x-ui::pillbox.option`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `value` | string | `text` | Value stored in the array. |
| `selected-label` | string | `null` | Pill text when picked. |
| `icon · color · meta` | string | `null` | Icon, color and text on the right. |
| `disabled · filterable` | boolean | `false · true` | Blocks the option; filterable false never hides when filtering. |

### `x-ui::pillbox.option.create`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `min-length` | int | `1` | Characters typed before it shows up. |
| `modal` | string | `null` | Opens that modal instead of calling wire:click. |

### `x-ui::pillbox.option.empty`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `when-loading` | string | `null` | Text while Livewire is searching. |

### `x-ui::pillbox.input · .search`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `placeholder` | string | `null · Filter…` | Field text; wire:model goes to the input. |

## Accessibility

- Each × has aria-label "Remove" followed by the pill text.
- With suggestions, the field is role="combobox" with a role="listbox" list.
- Clicking anywhere in the field focuses the text.
- Leaving the field adds what was typed, so the text is not lost.
