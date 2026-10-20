# Select · `<x-ui::select>`

Pick from a list. Native by default; with variant="listbox" it gains search, multiple options and server-side search, like Flux.

## Examples

### Native

The default is the browser <select>: fast, accessible and with the mobile picker wheel.

```blade
<x-ui::select label="Pipeline stage" placeholder="Choose a stage" class="max-w-sm">
    <x-ui::select.option value="lead">Lead</x-ui::select.option>
    <x-ui::select.option value="proposal">Proposal</x-ui::select.option>
    <x-ui::select.option value="negotiation">Negotiation</x-ui::select.option>
</x-ui::select>
```

### Listbox

variant="listbox" replaces the native control with a custom list that accepts an icon, color and meta on each option.

```blade
<x-ui::select variant="listbox" label="Owner" placeholder="Choose someone" value="ana" class="max-w-sm">
    <x-ui::select.option value="ana" icon="user" meta="12 deals">Ana Souza</x-ui::select.option>
    <x-ui::select.option value="bruno" icon="user" meta="8 deals">Bruno Lima</x-ui::select.option>
    <x-ui::select.option value="carla" icon="user" meta="5 deals">Carla Dias</x-ui::select.option>
</x-ui::select>
```

### Searchable and multiple

searchable filters, in the browser, what is already on the page; multiple stores an array and keeps the list open while you choose.

```blade
<x-ui::select variant="listbox" searchable multiple label="Industries" placeholder="All industries" search-placeholder="Filter industries…" :value="['health', 'education']" class="max-w-sm">
    <x-ui::select.option value="health" color="#16A34A">Health</x-ui::select.option>
    <x-ui::select.option value="education" color="#2563EB">Education</x-ui::select.option>
    <x-ui::select.option value="retail" color="#C2850F">Retail</x-ui::select.option>
    <x-ui::select.option value="manufacturing" color="#7C3AED">Manufacturing</x-ui::select.option>
    <x-ui::select.option value="services" color="#DC2626">Services</x-ui::select.option>
</x-ui::select>
```

### Server-side search

:filter="false" turns off browser filtering and the input slot sends the text to Livewire, which returns only the matching options. Keep the selected ones in the list so their labels do not disappear.

```blade
<x-ui::select variant="listbox" multiple :filter="false" wire:model.live="companyIds" label="Companies" placeholder="All companies" class="max-w-sm">
    <x-slot:input>
        <x-ui::select.input wire:model.live.debounce.250ms="companySearch" placeholder="Search company…" />
    </x-slot:input>
    <x-ui::select.option value="1" color="#2563EB">Acme Inc.</x-ui::select.option>
    <x-ui::select.option value="2" color="#16A34A">Smile Clinic</x-ui::select.option>
    <x-slot:empty>
        <x-ui::select.option.empty when-loading="Searching…">No companies found.</x-ui::select.option.empty>
    </x-slot:empty>
</x-ui::select>
```

```php
public array $companyIds = [];

public string $companySearch = '';

#[Computed]
public function companies(): Collection
{
    return Company::query()
        ->where('name', 'like', '%'.trim($this->companySearch).'%')
        ->orWhereIn('id', $this->companyIds) // selected ones stay in the list
        ->orderBy('name')
        ->limit(20)
        ->get();
}
```

## API

The native select forwards attributes to the <select>. With variant="listbox", wire:model goes to the list (a string, or an array with multiple) and name sends hidden inputs (name[] with multiple). Default texts such as "Select…" and "No results." follow the app locale.

### `x-ui::select`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | string | `default` | default (native) · listbox · combobox (listbox with search on). |
| `label · description · error` | string | `null` | Same as the other fields. |
| `placeholder` | string | `null` | Text when nothing is selected. |
| `size` | string | `base` | sm · base · lg (native). |
| `searchable` | boolean | `false` | Search box that filters the loaded options. |
| `multiple` | boolean | `false` | Multiple options; the value becomes an array. |
| `filter` | boolean | `true` | false leaves filtering to the server. |
| `search-placeholder · empty` | string | `Filter… · No results.` | Search and empty-list texts. |
| `slots` | input · empty | `—` | Search bound to Livewire; empty-list message. |

### `x-ui::select.option`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `value` | string | `null` | Option value. |
| `icon · color · meta` | string | `null` | Listbox only: icon, color and text on the right. |
| `selected-label` | string | `null` | Text shown in the field when selected. |
| `filterable` | boolean | `true` | false never hides it while filtering. |

### `x-ui::select.input`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `placeholder` | string | `Filter…` | Search text; wire:model goes to the input. |

### `x-ui::select.option.create · .empty`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `min-length · modal` | int · string | `1 · null` | create: shows up after this much text; opens a modal or calls wire:click. |
| `when-loading` | string | `null` | empty: text while Livewire is searching. |

## Accessibility

- The native select uses the system control, with the picker wheel on mobile.
- The listbox opens with ↓, Enter or click; ↑↓ move, Enter selects and Esc closes, returning focus.
- The list is role="listbox" (aria-multiselectable with multiple) and each option is role="option" with aria-selected.
- The search is role="combobox" with aria-activedescendant pointing at the active option.
