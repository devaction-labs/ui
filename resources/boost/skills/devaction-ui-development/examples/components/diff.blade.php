{{-- Diff: a patch hunk with an AI review thread and a suggestion reply — see references/diff.md --}}
@php
    $patch = "@@ -12,9 +12,11 @@ class Milestone extends Model
 class Milestone extends Model
 {
     protected \$casts = ['amount' => 'integer'];
-    public array \$required = ['title', 'amount', 'contract_id'];
+    public array \$required = ['title', 'amount', 'contract_id', 'due_on'];

     public function rules(): array
     {
+        return [
+            'due_on' => ['required', 'date'],
+        ];
-        return [];
     }
 }";
@endphp

<x-ui::diff path="app/Models/Milestone.php" :seen="false" class="w-full" :patch="$patch">
    <x-ui::diff.thread line="18">
        <x-ui::diff.comment author="DevAction Reviewer" ai when="now">due_on is now required, but old milestones have no date. The migration has to fill the existing records first, otherwise saving any old milestone fails.</x-ui::diff.comment>
        <x-ui::diff.comment author="Alex" when="2 min ago" suggestion-from="            'due_on' => ['required', 'date']," suggestion-to="            'due_on' => ['nullable', 'date'],">Makes sense. Can you suggest the conditional validation until the migration runs?</x-ui::diff.comment>
    </x-ui::diff.thread>
</x-ui::diff>
