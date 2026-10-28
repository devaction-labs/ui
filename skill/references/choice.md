---
kind: component
group: Form
components: [checkbox, checkbox.group, radio, radio.group]
---
# Choice · `<x-ui::choice>`

Checkbox, radio and choice cards built on native inputs. They work in plain forms and with wire:model on the input or the group.

## Examples

### Checkbox

Checked, partial (indeterminate) and disabled, with label and description.

```blade
<div class="flex w-full max-w-md flex-col">
    <x-ui::checkbox label="Select all repositories" indeterminate />
    <x-ui::checkbox label="sge-api" description="Watched since March." checked />
    <x-ui::checkbox label="hub-api" description="No open PRs." />
    <x-ui::checkbox label="ayram-web" description="Available on the Agency plan." disabled />
</div>
```

### Radio

x-ui::radio.group builds the radiogroup; wire:model on the group receives the chosen value.

```blade
<x-ui::radio.group label="Contract model" name="model" class="w-full max-w-md">
    <x-ui::radio value="fixed" label="Fixed scope" description="Fixed price per delivered milestone." checked />
    <x-ui::radio value="hours" label="Hour bank" description="Bills what was used in the month." />
    <x-ui::radio value="retainer" label="Retainer" description="Monthly fee with included hours." />
</x-ui::radio.group>
```

### Choice cards

variant="cards" for choices that need more context. The chosen one gets a dark border and a soft background.

```blade
<x-ui::radio.group label="How to publish the review" variant="cards" name="publishing" class="w-full max-w-xl">
    <x-ui::radio value="draft" icon="file-edit" label="As a draft" description="You approve it before it goes to GitHub." checked />
    <x-ui::radio value="direct" icon="sent" label="Straight to the PR" description="The AI publishes as soon as it finishes." />
</x-ui::radio.group>
```

### Checkbox group and pills

x-ui::checkbox.group with wire:model receives an array. variant="pills" for filters and tags.

```blade
<div class="grid w-full max-w-xl gap-6">
    <x-ui::checkbox.group label="Notify via" description="Choose where to receive review notifications.">
        <x-ui::checkbox value="email" label="Email" checked />
        <x-ui::checkbox value="whatsapp" label="WhatsApp" />
        <x-ui::checkbox value="slack" label="Slack" />
    </x-ui::checkbox.group>
    <x-ui::checkbox.group label="Stack" variant="pills">
        <x-ui::checkbox value="elixir" label="Elixir" checked />
        <x-ui::checkbox value="phoenix" label="Phoenix" checked />
        <x-ui::checkbox value="laravel" label="Laravel" />
        <x-ui::checkbox value="livewire" label="Livewire" />
    </x-ui::checkbox.group>
</div>
```

## API

HTML attributes (name, value, checked, disabled, wire:model) go to the <input>. On the group, wire:model and x-model bind the value of all inputs.

### `x-ui::checkbox`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description` | string | `null` | Text next to the box. |
| `indeterminate` | boolean | `false` | Partial state, with a dash. |
| `icon` | string | `null` | Icon on the card or pill. |

### `x-ui::checkbox.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description` | string | `null` | Fieldset legend. |
| `variant` | default \| cards \| pills | `default` | List, cards or pills. |
| `wire:model` | array | `—` | Checked values. |

### `x-ui::radio`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description · icon` | string | `null` | Like the checkbox. |

### `x-ui::radio.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description` | string | `null` | Radiogroup legend. |
| `variant` | default \| cards | `default` | List or cards. |
| `name` | string | `null` | Name shared by the radios. |
| `wire:model` | string | `—` | Chosen value. |

## Accessibility

- Native inputs inside a <label>: clicking the text checks it and the keyboard works like in the browser.
- Groups use a <fieldset> with a <legend>; radios sit in role="radiogroup".
- Visible focus ring; partial state announced by the native indeterminate.
- Pills keep the input accessible (sr-only) for screen readers.
