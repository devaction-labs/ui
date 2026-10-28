---
kind: component
group: Form
components: [editor]
---
# Editor · `<x-ui::editor>`

Rich text for delivery notes, descriptions and comments. Formatting toolbar, keyboard shortcuts and a "/" command menu. No dependencies.

## Examples

### Delivery notes

The full toolbar. Select text and use ⌘B or ⌘I; on an empty line, type / to insert a heading, list, quote or code. The actions slot puts buttons at the end of the toolbar.

```blade
<x-ui::editor
    label="Delivery notes"
    description="Explain what changed in this milestone. It goes to the client together with the sign-off."
    value="<h2>What changed in M3</h2><ul><li>Tithes and offerings entry with an <strong>audit trail</strong></li><li>Cash reconciliation by period</li><li>Monthly treasurer report as PDF</li></ul><p>Left out: <code>refunds</code>, which go into Amendment 01.</p>"
    class="w-full max-w-2xl"
>
    <x-slot:actions>
        <x-ui::button size="sm" variant="ai" icon="sparkles">AI</x-ui::button>
    </x-slot:actions>
</x-ui::editor>
```

### Compact toolbar

toolbar picks the buttons; | separates the groups. Good for comments.

```blade
<x-ui::editor toolbar="bold italic code | bullet | link" placeholder="Write a comment…" class="w-full max-w-2xl" />
```

### @ mentions and / commands

Type @ to mention someone (↑↓ and ↵ pick; the mention is locked into the text) or / on an empty line for the commands, including your own. editor-mention and editor-command report what was picked.

```blade
<x-ui::editor
    toolbar="bold italic code | bullet | link"
    placeholder="Write… use @ to mention and / for commands"
    :mentions="[
        ['value' => 'alex', 'label' => 'Alex', 'description' => 'Dev · online now', 'color' => 'ink'],
        ['value' => 'carlos', 'label' => 'Carlos André', 'description' => 'Dev · SGE', 'color' => 'blue'],
        ['value' => 'reviewer', 'label' => 'DevAction Reviewer', 'description' => 'AI · replies on the PR', 'icon' => 'sparkles', 'color' => 'ai'],
    ]"
    :commands="[
        ['label' => '/task', 'description' => 'Create a task', 'icon' => 'task-01', 'name' => 'task'],
        ['label' => '/tag', 'description' => 'Label', 'icon' => 'flag-02', 'name' => 'tag'],
    ]"
    x-on:editor-command="console.log('command', $event.detail.name)"
    class="w-full max-w-2xl"
/>
```

```php
// People searched on the server: mention-search="mentionQuery" receives the text after the @
public string $mentionQuery = '';

#[Computed]
public function people(): array
{
    return User::where('name', 'like', "%{$this->mentionQuery}%")
        ->limit(6)
        ->get()
        ->map(fn (User $user) => ['value' => $user->id, 'label' => $user->name, 'description' => $user->role])
        ->all();
}

// <x-ui::editor wire:model="body" :mentions="$this->people" mention-search="mentionQuery" />
```

## API

wire:model receives the HTML. Sanitize it on the server (for example, with an allow-list of tags) before showing it to other people. Pasting always comes in as plain text. Toolbar labels follow the app locale.

### `x-ui::editor`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `mentions` | array | `[]` | @ opens the people list: value, label, description, avatar, icon, color. |
| `mention-search` | string | `null` | Livewire property that receives the text after the @. |
| `commands` | array | `[]` | Extra / commands: label, description, icon, name; they fire editor-command. |
| `label · description · error` | string | `null` | Same as the other fields. |
| `value` | string | `''` | Initial HTML. |
| `toolbar` | string | `full` | heading bold italic strike code bullet ordered blockquote codeblock link undo redo, groups split by \|. |
| `placeholder` | string | `Write here…` | Text when the editor is empty. |
| `name` | string | `null` | Hidden input with the HTML. |
| `slot actions` | slot | `—` | Buttons at the end of the toolbar. |

## Accessibility

- The area is role="textbox" with aria-multiline; the toolbar is role="toolbar".
- Formatting buttons have an aria-label and aria-pressed reflecting the selection.
- The buttons do not steal focus from the text, so the selection stays put.
- The "/" menu moves with ↑↓, picks with ↵ and closes with Esc.
