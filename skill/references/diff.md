---
kind: component
group: Data
components: [diff, diff.comment, diff.composer, diff.thread]
---
# Diff · `<x-ui::diff>`

A file diff with conversations pinned to lines: comment, reply, suggest a change and resolve, like in a PR review.

## Examples

### Review with AI

Pass the GitHub patch. The + shows when you hover a line and opens a comment; the conversation on line 18 has a suggested change. Resolve collapses it and the line's + reopens it.

```blade
<x-ui::diff path="app/Models/Milestone.php" :seen="false" class="w-full" patch="@@ -12,9 +12,11 @@ class Milestone extends Model
 class Milestone extends Model
 {
     protected $casts = ['amount' => 'integer'];
-    public array $required = ['title', 'amount', 'contract_id'];
+    public array $required = ['title', 'amount', 'contract_id', 'due_on'];

     public function rules(): array
     {
+        return [
+            'due_on' => ['required', 'date'],
+        ];
-        return [];
     }
 }">
    <x-ui::diff.thread line="18">
        <x-ui::diff.comment author="DevAction Reviewer" ai when="now">due_on is now required, but old milestones have no date. The migration has to fill the existing records first, otherwise saving any old milestone fails.</x-ui::diff.comment>
        <x-ui::diff.comment author="Alex" when="2 min ago" suggestion-from="            'due_on' => ['required', 'date']," suggestion-to="            'due_on' => ['nullable', 'date'],">Makes sense. Can you suggest the conditional validation until the migration runs?</x-ui::diff.comment>
    </x-ui::diff.thread>
</x-ui::diff>
```

## API

With comment="addComment" Livewire receives (line, text) when someone comments; resolve and apply take any Livewire expression. Without Livewire, listen to diff-comment, thread-resolved and diff-apply-suggestion.

### `x-ui::diff`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `path` | string | `null` | File path at the top, with +/−. |
| `patch` | string | `null` | Unified diff (@@ -a,b +c,d @@). |
| `lines` | array | `[]` | Or the lines: type (add · del · ctx), old, new and code. |
| `comment` | string | `null` | Livewire method called with (line, text). |
| `commentable` | boolean | `true` | Shows the + on lines. |
| `seen` | boolean | `null` | "Viewed" checkbox at the top. |

### `x-ui::diff.thread`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `line` | int | `—` | Line (of the new file) where the conversation sits. |
| `resolve` | string | `null` | Livewire expression for Resolve; fires thread-resolved. |
| `resolved` | boolean | `false` | Starts collapsed. |

### `x-ui::diff.comment`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `author · when · avatar` | string | `null` | Who, when and photo. |
| `ai` | boolean | `false` | AI badge and agent avatar. |
| `suggestion-from · suggestion-to` | string | `null` | Suggested change (current and proposed line). |
| `apply` | string | `null` | Livewire expression for "Apply suggestion". |

## Accessibility

- Each line's + is a "Comment on line N" button; it appears on keyboard focus, not only on hover.
- Additions and removals do not rely on color alone: lines show + and −.
- The comment field is labelled and submits with ⌘↵ or Ctrl+↵.
