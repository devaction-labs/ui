---
kind: component
group: Data
components: [properties, properties.item, properties.section]
---
# Properties · `<x-ui::properties>`

The details column next to a task, card or contract: small sections with label and value rows.

## Examples

### Issue sidebar

Sections are split by a line. The action slot puts an edit button next to the heading; mono suits estimates and keys.

```blade
<x-ui::properties class="w-[300px]">
    <x-ui::properties.section heading="Assignees">
        <x-slot:action><x-ui::button size="xs" variant="ghost" icon="settings-02" aria-label="Edit assignees" /></x-slot:action>
        <span class="flex items-center gap-2"><x-ui::avatar name="Carlos André" color="cyan" size="xs" circle />Carlos André</span>
        <span class="flex items-center gap-2"><x-ui::avatar name="Alex Nogueira" color="violet" size="xs" circle />Alex Nogueira</span>
    </x-ui::properties.section>
    <x-ui::properties.section heading="Labels">
        <div class="flex flex-wrap gap-1.5">
            <x-ui::badge color="info" size="sm">backend</x-ui::badge>
            <x-ui::badge color="risk" size="sm">risk: money</x-ui::badge>
        </div>
    </x-ui::properties.section>
    <x-ui::properties.section heading="Board">
        <x-ui::properties.item label="Column">In progress</x-ui::properties.item>
        <x-ui::properties.item label="Due">Thu 15 · tomorrow</x-ui::properties.item>
        <x-ui::properties.item label="Estimate" mono>5 pts</x-ui::properties.item>
    </x-ui::properties.section>
    <x-ui::properties.section heading="Milestone">
        <x-ui::progress value="62" color="green" label="Delivery 3 · Finance" show-value />
    </x-ui::properties.section>
</x-ui::properties>
```

### Details panel in cards

variant="card" puts each section in its own card, as in the panel style. stacked places long values under the label.

```blade
<x-ui::properties variant="card" class="w-[320px]">
    <x-ui::properties.section heading="Details">
        <x-ui::properties.item label="Reporter">Fernando Pinto</x-ui::properties.item>
        <x-ui::properties.item label="Sprint">Sprint 3.4</x-ui::properties.item>
        <x-ui::properties.item label="Story points" mono>5</x-ui::properties.item>
        <x-ui::properties.item label="Priority"><x-ui::badge color="risk" size="sm">High</x-ui::badge></x-ui::properties.item>
    </x-ui::properties.section>
    <x-ui::properties.section heading="Automation">
        <x-ui::properties.item label="When the linked PR is merged" stacked>Move to Done and notify the reporter</x-ui::properties.item>
    </x-ui::properties.section>
</x-ui::properties>
```

## API

### `x-ui::properties`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | plain \| card | `plain` | Sections split by a line, or each in its own card. |
| `label` | string | `Details` | aria-label of the aside. |

### `x-ui::properties.section`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `heading` | string | `null` | Small section title. |
| `action` | slot | `—` | Button on the right of the title (edit, add). |

### `x-ui::properties.item`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `null` | Label on the left. |
| `mono` | boolean | `false` | Value in Geist Mono. |
| `stacked` | boolean | `false` | Label above and value below, for long values. |

## Accessibility

- Renders an <aside> with its own label, so it shows up as a landmark.
- Each section heading is an <h4>, which lets screen readers jump between sections.
- Icon-only action buttons need an aria-label, as in the example.
