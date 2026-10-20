# Empty state · `<x-ui::empty-state>`

Every empty state says what the area is, why it is empty and what the next step is, with a single primary button.

## Examples

### First use

variant="card" is the default: a card with the icon over the grid pattern, the same motif as the sign-in screens.

```blade
<x-ui::empty-state
    icon="folder-add"
    label="Projects"
    heading="Your first project starts here"
    text="A project brings together the contract, milestone scope, tasks and the PRs that deliver each item."
    class="w-full max-w-xl"
>
    <x-slot:actions>
        <x-ui::button variant="primary" icon="add-01">Create project</x-ui::button>
        <x-ui::button icon="github">Import from GitHub</x-ui::button>
    </x-slot:actions>
</x-ui::empty-state>
```

### Area the AI will fill

color="ai" turns the icon amber: the area fills itself once the AI has done its work.

```blade
<x-ui::empty-state
    color="ai"
    icon="sparkles"
    label="Learning"
    heading="Your track shows up after the 1st review"
    text="Lessons come from your own PRs. As soon as the AI reviews the first one, the track begins."
    class="w-full max-w-xl"
>
    <x-slot:actions>
        <x-ui::button variant="primary" icon="sparkles">Request a review now</x-ui::button>
    </x-slot:actions>
</x-ui::empty-state>
```

### Small empty states

variant="dashed" inside tables, lists and search. The default slot accepts links in the text.

```blade
<div class="grid w-full max-w-2xl gap-3 sm:grid-cols-2">
    <x-ui::empty-state variant="dashed" icon="search-01" heading="Nothing for “refunds”">
        Try another term or <a href="#" class="text-ink underline underline-offset-4">clear the filters</a>.
    </x-ui::empty-state>
    <x-ui::empty-state variant="dashed" icon="git-pull-request" heading="No PRs to review" text="The AI lets you know when a new one arrives." />
</div>
```

### Success and error in lists

An empty queue and a loading failure are not neutral empty states: use Alert, which already has color, icon and role.

```blade
<div class="flex w-full max-w-xl flex-col gap-2.5">
    <x-ui::alert variant="success" heading="Review queue cleared" text="No PRs waiting for you." />
    <x-ui::alert variant="danger" heading="Couldn't load the PRs" text="GitHub did not respond." inline>
        <x-slot:actions>
            <x-ui::button size="sm" variant="danger">Try again</x-ui::button>
        </x-slot:actions>
    </x-ui::alert>
</div>
```

## API

### `x-ui::empty-state`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | card \| dashed \| plain | `card` | card for full screens; dashed and plain inside lists. |
| `color` | default \| ai | `default` | ai paints the icon amber. |
| `icon` | string \| slot | `null` | Hugeicons icon; the slot accepts your own SVG. |
| `label` | string | `null` | Mono label with the area name. |
| `heading` | string | `null` | What is empty, in one sentence. |
| `text` | string | `null` | Why it is empty and what happens next. |
| `actions` | slot | `null` | One primary button, at most one secondary. |

## Accessibility

- The heading is an <h3>: the empty area stays navigable by headings.
- The icon and the grid pattern are decorative (aria-hidden).
- Actions are real buttons or links, never clickable text.
- Text keeps a minimum 4.5:1 contrast in both themes.
