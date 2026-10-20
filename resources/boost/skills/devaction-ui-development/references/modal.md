# Modal · `<x-ui::modal>`

For interrupting decisions: confirm, a short form, a notice. Focus is trapped, Esc closes and the page does not scroll behind it.

## Examples

### Edit profile

The same setup as Flux: modal.trigger opens the modal by name; heading, text and spacer arrange the content. class changes the width (md:w-96).

```blade
<x-ui::modal.trigger name="edit-profile">
    <x-ui::button>Edit profile</x-ui::button>
</x-ui::modal.trigger>

<x-ui::modal name="edit-profile" class="md:w-96">
    <div class="space-y-6">
        <div>
            <x-ui::heading size="lg">Update profile</x-ui::heading>
            <x-ui::text class="mt-2">Make changes to your personal details.</x-ui::text>
        </div>

        <x-ui::input label="Name" placeholder="Your name" />

        <x-ui::input label="Date of birth" type="date" />

        <div class="flex">
            <x-ui::spacer />

            <x-ui::button type="submit" variant="primary">Save changes</x-ui::button>
        </div>
    </div>
</x-ui::modal>
```

```php
use DevAction\Ui\Livewire\InteractsWithModals;
use DevAction\Ui\Ui;

class ShowPost extends Component
{
    use InteractsWithModals;

    public function delete(): void
    {
        // Control modals anywhere on the page...
        Ui::modal('confirm')->show();
        Ui::modal('confirm')->close();

        // Control modals from this component...
        $this->modal('confirm')->show();
        $this->modal('confirm')->close();

        // Close every modal on the page...
        Ui::modals()->close();
    }
}
```

### Confirmation

modal.trigger opens by name; modal.close closes. The footer slot holds the actions, with a divider and a soft background.

```blade
<x-ui::modal.trigger name="publish-review">
    <x-ui::button variant="primary" icon="git-pull-request">Publish review</x-ui::button>
</x-ui::modal.trigger>

<x-ui::modal name="publish-review" size="sm" :closable="false">
    <div class="flex gap-4">
        <span class="flex size-11 shrink-0 items-center justify-center rounded-xl border border-warn-line bg-warn-bg text-accent">
            <x-ui::icon name="git-pull-request" />
        </span>
        <div class="flex flex-col gap-1.5">
            <h2 class="text-lg font-semibold">Publish the review on GitHub?</h2>
            <p class="text-sm leading-[1.55] text-ink-3">2 comments go to PR #142 on behalf of DevAction AI. Carlos André is notified right away.</p>
        </div>
    </div>
    <x-slot:footer>
        <x-ui::modal.close><x-ui::button>Cancel</x-ui::button></x-ui::modal.close>
        <x-ui::modal.close><x-ui::button variant="primary">Publish</x-ui::button></x-ui::modal.close>
    </x-slot:footer>
</x-ui::modal>
```

### Short form

The close button is there by default. The shortcut in the footer is just text: submitting is up to your form.

```blade
<x-ui::modal.trigger name="new-task">
    <x-ui::button icon="add-01">New task</x-ui::button>
</x-ui::modal.trigger>

<x-ui::modal name="new-task">
    <div class="flex flex-col gap-1">
        <h2 class="text-lg font-semibold">New task</h2>
        <p class="text-sm text-muted">In SGE · M3 Finance</p>
    </div>
    <label class="mt-5 flex flex-col gap-1.5 text-sm font-medium">
        Title
        <input type="text" value="Fix N+1 in contributions" class="h-11 rounded-xl border border-line-strong bg-card px-3.5 font-normal outline-none focus:border-ink focus:ring-4 focus:ring-ink/10" />
    </label>
    <x-slot:footer>
        <span class="mr-auto text-xs text-muted"><x-ui::kbd>⌘↵</x-ui::kbd> creates</span>
        <x-ui::modal.close><x-ui::button>Cancel</x-ui::button></x-ui::modal.close>
        <x-ui::button variant="primary">Create task</x-ui::button>
    </x-slot:footer>
</x-ui::modal>
```

### Success notice

Centered content for notices that ask for a next step. dismissible="false" requires picking an action.

```blade
<x-ui::modal.trigger name="github-connected">
    <x-ui::button icon="github">Connect GitHub</x-ui::button>
</x-ui::modal.trigger>

<x-ui::modal name="github-connected" size="sm" :closable="false" :dismissible="false">
    <div class="flex flex-col items-center gap-3 text-center">
        <span class="flex size-14 items-center justify-center rounded-2xl border border-ok-line bg-ok-bg text-ok-fg">
            <x-ui::icon name="checkmark-circle-02" class="size-6.5" />
        </span>
        <h2 class="text-xl font-semibold">GitHub connected</h2>
        <p class="max-w-[380px] text-sm leading-[1.55] text-ink-3">6 repositories found. The AI starts reviewing open PRs in the ones you watch.</p>
        <x-ui::modal.close><x-ui::button variant="primary" size="lg" class="mt-2">Pick repositories</x-ui::button></x-ui::modal.close>
    </div>
</x-ui::modal>
```

### Flyout

flyout opens against the side for longer content; variant="floating" lifts it off the edge. position changes the side.

```blade
<x-ui::modal.trigger name="profile-flyout">
    <x-ui::button>Edit profile</x-ui::button>
</x-ui::modal.trigger>
<x-ui::modal.trigger name="filters-flyout">
    <x-ui::button icon="filter">Filters</x-ui::button>
</x-ui::modal.trigger>

<x-ui::modal name="profile-flyout" flyout variant="floating">
    <h2 class="text-lg font-semibold">Edit profile</h2>
    <p class="mt-1 text-sm text-muted">Name, email and photo shown in reviews.</p>
    <x-slot:footer>
        <x-ui::modal.close><x-ui::button variant="filled">Cancel</x-ui::button></x-ui::modal.close>
        <x-ui::button variant="primary">Save</x-ui::button>
    </x-slot:footer>
</x-ui::modal>

<x-ui::modal name="filters-flyout" flyout position="left">
    <h2 class="text-lg font-semibold">Filters</h2>
    <p class="mt-1 text-sm text-muted">Project, owner and status.</p>
</x-ui::modal>
```

## API

With Livewire: wire:model on the modal binds open/closed to a property; the DevAction\Ui\Livewire\InteractsWithModals trait gives you $this->modal('name')->show(); and DevAction\Ui\Ui::modal('name') does the same from any class running in a Livewire request (action, service), like Flux::modal(). In JavaScript: $modal('name').show() and $modals().close().

### `x-ui::modal`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | string | `null` | Identifier used by triggers and by JS. |
| `size` | sm \| md \| lg | `md` | Width of 400, 520 or 720 px. |
| `flyout` | boolean | `false` | Opens against the side. |
| `position` | right \| left \| bottom | `right` | Flyout side. |
| `variant` | default \| floating \| bare | `default` | floating lifts the flyout off the edge; bare removes frame and padding. |
| `dismissible` | boolean | `true` | Clicking outside closes. |
| `escapable` | boolean | `true` | Esc closes. |
| `closable` | boolean | `true` | Shows the close button. |
| `footer` | slot | `null` | Actions with a divider and soft background. |
| `wire:model` | string | `—` | Binds the state to a Livewire property. |
| `x-on:close · x-on:cancel` | event | `—` | close: closed for any reason. cancel: Esc or click outside. |

### `x-ui::modal.trigger`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | string | `—` | Modal it opens. |
| `shortcut` | string | `null` | Shortcut with Alpine modifiers, e.g. cmd.k. |

### `x-ui::modal.close`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `default` | slot | `—` | Element that closes the modal it is in. |

### `JS`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `$modal(name).show() · .close()` | Alpine | `—` | Opens or closes by name. |
| `$modals().close()` | Alpine | `—` | Closes them all. |
| `DevActionUI.modal(name)` | window | `—` | The same, outside Alpine. |

## Accessibility

- Uses <dialog> with showModal(): the rest of the page becomes inert and focus stays in the modal.
- Esc closes (turn it off with escapable="false") and focus returns to the opener.
- Page scroll is locked while any modal is open.
- The close button has an aria-label; the heading should be the first text in the modal.
