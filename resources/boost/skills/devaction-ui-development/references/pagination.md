---
kind: component
group: Navigation
components: [pagination]
---
# Pagination · `<x-ui::pagination>`

Moves between the pages of a list. Takes the Laravel paginator and works with regular links or with Livewire's WithPagination.

## Examples

### With numbers

With paginate(): a summary on the left and numbers with ellipses (1 2 3 … 9). The current page is filled.

```blade
<x-ui::pagination
    :paginator="new \Illuminate\Pagination\LengthAwarePaginator(range(1, 10), 90, 10, 2, ['path' => '#'])"
    class="w-full"
/>
```

### Simple

With simplePaginate() only previous and next show up.

```blade
<x-ui::pagination
    :paginator="new \Illuminate\Pagination\Paginator(range(1, 11), 10, 3, ['path' => '#'])"
    class="w-full"
/>
```

## API

In a Livewire component with WithPagination, pass livewire: the buttons call setPage(), previousPage() and nextPage() without reloading the page. The summary and labels follow the app locale.

### `x-ui::pagination`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `paginator` | Paginator | `—` | Result of paginate() or simplePaginate(). |
| `livewire` | boolean | `false` | Uses the WithPagination methods instead of links. |
| `summary` | boolean | `true` | "Showing 11–20 of 90" on the left. |
| `on-each-side` | int | `1` | Pages beside the current one. |

## Accessibility

- nav with aria-label="Pagination"; the current page has aria-current="page".
- Arrows have aria-label "Previous" and "Next"; at the limits they get aria-disabled.
- Each number has aria-label "Page N".
- Hidden when there is only one page.
