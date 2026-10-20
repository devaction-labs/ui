# Activity · `<x-ui::activity>`

A single conversation for a task: comments, events and the AI review on one rail, with the comment box at the end.

## Examples

### Issue timeline

Comments get a header with author and time; events are one line on the rail; the AI review uses variant="ai". The composer slot sits at the end, joined to the rail.

```blade
<x-ui::activity composer-name="Alex" composer-color="violet" class="w-full max-w-2xl">
    <x-ui::activity.comment name="Carlos André" color="cyan" when="2 days ago" role="Author">
        <p>Posting the installment and settling the cash account run in two transactions. If the second one fails, the installment stays posted without the settlement. Proposal: wrap both in DB::transaction.</p>
        <x-ui::checklist :progress="false">
            <x-ui::checklist.item checked>Post and settle in the same transaction</x-ui::checklist.item>
            <x-ui::checklist.item checked>Roll back if the receipt fails</x-ui::checklist.item>
            <x-ui::checklist.item>Generate the receipt after the commit</x-ui::checklist.item>
        </x-ui::checklist>
        <x-slot:footer>
            <x-ui::reactions :reactions="['🚀' => 1]" />
        </x-slot:footer>
    </x-ui::activity.comment>

    <x-ui::activity.event icon="tag-01" color="risk" name="Alex" avatar-color="violet" when="2 days ago">
        added <x-ui::badge color="risk" size="sm">risk: money</x-ui::badge>
    </x-ui::activity.event>
    <x-ui::activity.event icon="arrow-right-01" color="info" name="Fernando Pinto" avatar-color="amber" when="yesterday">
        moved from To do to <x-ui::badge color="info" size="sm">In progress</x-ui::badge>
    </x-ui::activity.event>
    <x-ui::activity.event icon="git-pull-request" color="ok" name="Carlos André" avatar-color="cyan" when="3 h ago">
        linked PR #142 to this task
    </x-ui::activity.event>

    <x-ui::activity.comment variant="ai" name="DevAction AI" verb="reviewed PR #142" when="2 h ago">
        <x-slot:badge><x-ui::badge color="risk" size="sm">2 issues</x-ui::badge></x-slot:badge>
        <p class="font-medium text-ink">The receipt is generated inside the transaction: if the PDF fails, the entry rolls back.</p>
        <p class="font-mono text-xs text-muted">app/Actions/PostInstallment.php:88</p>
    </x-ui::activity.comment>

    <x-ui::activity.comment name="Fernando Pinto" color="amber" when="1 h ago" edited>
        <p>The client wants the receipt right away. Can we keep that with the receipt after the commit?</p>
        <x-slot:footer>
            <x-ui::reactions :reactions="['👍' => 1, '🎉' => 1]" :selected="['👍']" />
        </x-slot:footer>
    </x-ui::activity.comment>

    <x-slot:composer>
        <x-ui::editor toolbar="bold italic code | bullet | link" placeholder="Write a comment…" />
    </x-slot:composer>
</x-ui::activity>
```

### Highlight, actions and Livewire

variant="highlight" marks the comment being replied to; the actions slot takes a menu. With Livewire, the composer posts through a method; reactions take react="toggleReaction" to call a method.

```blade
<x-ui::activity composer-name="Alex" composer-color="violet" class="w-full max-w-2xl">
    <x-ui::activity.comment name="Carlos André" color="cyan" when="40 min ago" verb="replied to Fernando" variant="highlight">
        <p>Yes: I generate the receipt in a queued job after the transaction commits and show a toast when it is ready.</p>
        <x-slot:actions>
            <x-ui::dropdown align="end">
                <x-ui::button size="xs" variant="ghost" icon="more-horizontal" aria-label="More" />
                <x-ui::menu>
                    <x-ui::menu.item icon="edit-02">Edit</x-ui::menu.item>
                    <x-ui::menu.item icon="delete-02" variant="danger">Delete</x-ui::menu.item>
                </x-ui::menu>
            </x-ui::dropdown>
        </x-slot:actions>
        <x-slot:footer>
            <x-ui::reactions :reactions="['👍' => 2]" />
        </x-slot:footer>
    </x-ui::activity.comment>
    <x-slot:composer>
        <x-ui::editor wire:model="draft" toolbar="bold italic | link" placeholder="Write a comment…">
            <x-slot:actions><x-ui::button size="sm" variant="primary" wire:click="comment">Comment</x-ui::button></x-slot:actions>
        </x-ui::editor>
    </x-slot:composer>
</x-ui::activity>
```

```php
public string $draft = '';

public function comment(): void
{
    $this->validate(['draft' => 'required|string|max:10000']);

    $this->task->comments()->create(['user_id' => auth()->id(), 'body' => $this->draft]);
    $this->reset('draft');
}

public function toggleReaction(string $emoji): void
{
    $this->task->comments()->latest()->first()->toggleReaction(auth()->user(), $emoji);
}
```

## API

### `x-ui::activity`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `Activity` | aria-label of the section. |
| `composer-name` | string | `null` | Name for the avatar next to the composer. |
| `composer-color` | string | `auto` | Avatar color of the composer. |
| `composer` | slot | `—` | Comment box at the end of the rail (editor, textarea). |

### `x-ui::activity.comment`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | string | `null` | Author. |
| `color` | string | `auto` | Avatar color. |
| `avatar` | string | `null` | Avatar image URL. |
| `when` | string | `null` | When, already formatted ("2 h ago"). |
| `verb` | string | `commented` | Text after the name ("reviewed PR #142"). |
| `role` | string | `null` | Label on the right (Author, Client). |
| `edited` | boolean | `false` | Shows "· edited". |
| `variant` | default \| ai \| highlight | `default` | ai: AI avatar and amber background; highlight: dark border. |
| `badge · actions · footer` | slot | `—` | Right side of the header and the area under the body (reactions, replies). |

### `x-ui::activity.event`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `icon` | string | `information-circle` | Icon on the rail. |
| `color` | plain \| info \| ok \| warn \| risk | `plain` | Color of the rail icon. |
| `name` | string | `null` | Who did it. |
| `avatar-color` | string | `auto` | Avatar color. |
| `when` | string | `null` | When, already formatted. |

## Accessibility

- Renders a <section> with an <ol>: screen readers announce the number of entries.
- Each comment is an <article> with the author and time in its header.
- The rail is decorative (aria-hidden); event icons only repeat what the text says.
- The AI review is named in the header, not only by its amber color.
