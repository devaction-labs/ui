# File tree · `<x-ui::file-tree>`

Tree of the files changed in a PR: folders built from the paths, status, +/− and AI findings per file.

## Examples

### PR files

Click folders to open and close them, or use the arrow keys (← closes, → opens). T focuses the search; the tabs show only files with findings or new files.

```blade
<x-ui::file-tree
    value="app/Models/Milestone.php"
    class="h-[460px] w-[320px] rounded-2xl border border-line"
    :files="[
        ['path' => 'app/Models/Milestone.php', 'status' => 'M', 'additions' => 18, 'deletions' => 6, 'findings' => 2, 'level' => 'risk'],
        ['path' => 'app/Models/Scope.php', 'status' => 'M', 'additions' => 9, 'deletions' => 3, 'findings' => 1],
        ['path' => 'app/Billing/Invoice.php', 'status' => 'A', 'additions' => 64],
        ['path' => 'app/Livewire/MilestoneBoard.php', 'status' => 'M', 'additions' => 22, 'deletions' => 11, 'findings' => 1],
        ['path' => 'app/Livewire/LegacyReport.php', 'status' => 'D', 'deletions' => 48],
        ['path' => 'tests/Feature/MilestoneTest.php', 'status' => 'A', 'additions' => 41],
        ['path' => 'database/migrations/2026_10_14_add_due_on.php', 'status' => 'A', 'additions' => 12],
    ]"
/>
```

## API

wire:model receives the path of the selected file. The select-file event carries detail.path, handy for loading the diff with Livewire.

### `x-ui::file-tree`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `files` | array | `[]` | Files: path, status (A · M · D), additions, deletions, findings and level (risk · warn). |
| `heading` | string | `Changed files` | Heading and tree name. |
| `value` | string | `null` | Selected file; binds with wire:model. |
| `filters` | boolean | `true` | Search (T shortcut) and tabs All · With findings · New. |
| `select-file` | event | `—` | detail: { path }. |

## Accessibility

- role="tree" with role="treeitem", aria-level, aria-expanded on folders and aria-selected on files.
- Only one item is in the Tab order at a time; ↑ ↓ move, Home and End jump to the ends, ← → open, close and go up to the folder.
- The findings badge has screen reader text ("2 findings"); the status is also a letter, not just a color.
