# Combobox · `<x-ui::combobox>`

Select with search: a single choice from a long list, filtering as you type. Accents and capitals don’t get in the way of the filter.

## Examples

### Project

Color and meta on each option. ↓ opens, ↑↓ navigate, ↵ picks and Esc closes.

```blade
<x-ui::combobox label="Project" value="igreja" search-placeholder="Filter projects…" empty="No project found." class="max-w-sm">
    <x-ui::combobox.option value="igreja" color="#2563EB" meta="12 PRs">Igreja Viva</x-ui::combobox.option>
    <x-ui::combobox.option value="loja" color="#16A34A" meta="4 PRs">Ponto Store</x-ui::combobox.option>
    <x-ui::combobox.option value="clinica" color="#C2850F" meta="7 PRs">Sorriso Clínica</x-ui::combobox.option>
    <x-ui::combobox.option value="escola" color="#7C3AED" meta="2 PRs">Aprender School</x-ui::combobox.option>
    <x-ui::combobox.option value="academia" color="#DC2626" meta="archived" disabled>Forte Gym</x-ui::combobox.option>
</x-ui::combobox>
```

### With icons and an error

search adds terms to the filter. The validation error shows up through wire:model.

```blade
<x-ui::combobox label="Assignee" placeholder="Choose someone" error="Choose an assignee." class="max-w-sm">
    <x-ui::combobox.option value="ana" icon="user" search="design">Ana Souza</x-ui::combobox.option>
    <x-ui::combobox.option value="bruno" icon="user" search="backend">Bruno Lima</x-ui::combobox.option>
    <x-ui::combobox.option value="carla" icon="user" search="frontend">Carla Dias</x-ui::combobox.option>
</x-ui::combobox>
```

### Multiple options

multiple stores an array; the list stays open while choosing and each option gets a checkbox.

```blade
<x-ui::combobox multiple label="Projects" placeholder="All projects" :value="['igreja', 'clinica']" class="max-w-sm">
    <x-ui::combobox.option value="igreja" color="#2563EB">Igreja Viva</x-ui::combobox.option>
    <x-ui::combobox.option value="loja" color="#16A34A">Ponto Store</x-ui::combobox.option>
    <x-ui::combobox.option value="clinica" color="#C2850F">Sorriso Clínica</x-ui::combobox.option>
    <x-ui::combobox.option value="escola" color="#7C3AED">Aprender School</x-ui::combobox.option>
</x-ui::combobox>
```

### Server search

:filter="false" shows options exactly as the server sent them; the input slot binds the search to Livewire. combobox.option.create offers to create what was typed.

```blade
<x-ui::combobox wire:model.live="companyId" :filter="false" label="Company" placeholder="Choose the company" class="max-w-sm">
    <x-slot:input>
        <x-ui::combobox.input wire:model.live.debounce.250ms="companySearch" placeholder="Search company…" />
    </x-slot:input>
    <x-ui::combobox.option value="1" color="#2563EB">Acme Inc.</x-ui::combobox.option>
    <x-ui::combobox.option value="2" color="#16A34A">Sorriso Clínica</x-ui::combobox.option>
    <x-ui::combobox.option.create wire:click="createCompany" min-length="3">
        Create "<span wire:text="companySearch"></span>"
    </x-ui::combobox.option.create>
    <x-slot:empty>
        <x-ui::combobox.option.empty when-loading="Searching…">No company found.</x-ui::combobox.option.empty>
    </x-slot:empty>
</x-ui::combobox>
```

```php
public ?string $companyId = null;

public string $companySearch = '';

#[Computed]
public function companies(): Collection
{
    return Company::query()
        ->where('name', 'like', '%'.trim($this->companySearch).'%')
        ->orWhere('id', $this->companyId) // keep the chosen one in the list
        ->orderBy('name')
        ->limit(8)
        ->get();
}
```

## API

wire:model goes on x-ui::combobox and receives the option’s value (an array with multiple). With name, hidden inputs carry the value in plain forms. With server search, keep the chosen option in the list: the label is stored, but that option is the one shown as selected. Default texts follow the app locale (English by default, Portuguese with pt_BR).

### `x-ui::combobox`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description · error` | string | `null` | Like other fields. |
| `placeholder` | string | `Select…` | Text with no choice. |
| `search-placeholder` | string | `Filter…` | Search input text. |
| `empty` | string | `Nothing found.` | Text when the filter finds nothing. |
| `name · value` | string | `null` | For plain forms and initial value. |
| `multiple` | boolean | `false` | Multiple options; the value becomes an array. |
| `searchable` | boolean | `true` | false hides the search box. |
| `filter` | boolean | `true` | false leaves filtering to the server. |
| `slots` | input · empty | `—` | Search bound to Livewire (x-ui::combobox.input) and empty list. |

### `x-ui::combobox.option`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `value` | string | `text` | Option value. |
| `color · icon` | string | `null` | Color swatch or icon before the text. |
| `meta` | string | `null` | Mono detail on the right. |
| `search` | string | `null` | Extra terms for the filter. |
| `disabled` | boolean | `false` | Shown, but can’t be chosen. |
| `selected-label · filterable` | string · boolean | `null · true` | Text in the field when chosen; false never hides it in the filter. |

### `x-ui::combobox.input`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `placeholder` | string | `Filter…` | Search text; wire:model goes to the input. |

### `x-ui::combobox.option.create`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `min-length · modal` | int · string | `1 · null` | Shows after that much text; opens a modal or calls wire:click. |

### `x-ui::combobox.option.empty`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `when-loading` | string | `null` | Text while Livewire searches. |

## Accessibility

- Trigger with aria-haspopup="listbox" and aria-expanded; the search is role="combobox" with aria-activedescendant.
- Options with role="option" and aria-selected.
- Esc closes and returns focus to the trigger; it only intercepts Esc while open.
- The filter ignores accents: "clinica" finds "Clínica".
