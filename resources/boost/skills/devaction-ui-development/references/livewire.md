---
kind: guide
group: Guides
components: []
---
# Livewire with DevAction UI

## Fields and validation

`wire:model` sits on the component; the field label, description and the validation error for that property are handled by the component.

```blade
<form wire:submit="save" class="flex flex-col gap-4">
    <x-ui::input wire:model="form.name" label="Name" required />
    <x-ui::select wire:model="form.stage" label="Stage">
        <x-ui::select.option value="lead">Lead</x-ui::select.option>
    </x-ui::select>
    <x-ui::date-picker wire:model="form.due" label="Due date" />
    <x-ui::money-input wire:model="form.amount" label="Amount" />
    <x-ui::button type="submit" variant="primary">Save</x-ui::button>
</form>
```

## Server-side search (select, combobox, pillbox, autocomplete)

```blade
<x-ui::select variant="listbox" multiple :filter="false" wire:model.live="companyIds" placeholder="All companies">
    <x-slot:input><x-ui::select.input wire:model.live.debounce.250ms="companySearch" placeholder="Search company…" /></x-slot:input>
    @foreach ($this->companies as $company)
        <x-ui::select.option :value="(string) $company->id">{{ $company->name }}</x-ui::select.option>
    @endforeach
    <x-slot:empty><x-ui::select.option.empty when-loading="Searching…">No companies found.</x-ui::select.option.empty></x-slot:empty>
</x-ui::select>
```

```php
#[Computed]
public function companies(): Collection
{
    return Company::query()
        ->where(fn ($query) => $query->where('name', 'like', '%'.trim($this->companySearch).'%')
            ->orWhereIn('id', $this->companyIds)) // keep the selected ones
        ->orderBy('name')
        ->limit(20)
        ->get();
}
```

Create from the typed text with `x-ui::pillbox.option.create wire:click="createTag" min-length="2"` (or `combobox.option.create`).

## Modals

```blade
<x-ui::modal.trigger name="edit-profile"><x-ui::button>Edit profile</x-ui::button></x-ui::modal.trigger>

<x-ui::modal name="edit-profile" class="md:w-96">
    <x-ui::heading size="lg">Update profile</x-ui::heading>
    <x-ui::text class="mt-2">Make changes to your personal details.</x-ui::text>
    <div class="flex"><x-ui::spacer /><x-ui::button type="submit" variant="primary">Save</x-ui::button></div>
</x-ui::modal>

<x-ui::modal wire:model="showConfirm">…</x-ui::modal>   {{-- open state bound to a property --}}
```

```php
$this->modal('edit-profile')->close(); // trait InteractsWithModals
Ui::modal('confirm')->show();          // DevAction\Ui\Ui, anywhere during a Livewire request
```

JavaScript: `$modal('name').show()`, `$modals().close()`.

## Toasts

```php
$this->toast('Deal saved.', variant: 'success'); // trait InteractsWithToasts
```

JavaScript: `$toast({ text: 'Copied', variant: 'success' })`. The same `id` updates an existing toast (loading → success).

## Tables

```blade
<x-ui::table :paginate="$this->orders" selectable wire:model="selected">
    <x-ui::table.columns sticky>
        <x-ui::table.column sortable :sorted="$sortBy === 'date'" :direction="$sortDirection" wire:click="sort('date')">Date</x-ui::table.column>
    </x-ui::table.columns>
    <x-ui::table.rows>
        @foreach ($this->orders as $order)
            <x-ui::table.row :key="$order->id">
                <x-ui::table.cell variant="strong">{{ $order->total }}</x-ui::table.cell>
            </x-ui::table.row>
        @endforeach
    </x-ui::table.rows>
    <x-slot:bulk><x-ui::button size="sm" wire:click="archive">Archive</x-ui::button></x-slot:bulk>
</x-ui::table>
```

## Drag and drop

```blade
<x-ui::kanban>
    <x-ui::kanban.column id="lead" heading="Lead" sort="moveDeal" group="deals" :count-text="'$wire.totals.lead.count'">…cards…</x-ui::kanban.column>
</x-ui::kanban>
```

`moveDeal($id, $position, $stage)` persists the move; `count-text` updates counters instantly with `wire:text`.
