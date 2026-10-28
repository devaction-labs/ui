{{-- File tree: changed files of a PR with status, line counts and findings per file — see references/file-tree.md --}}
@php
    $files = [
        ['path' => 'app/Models/Milestone.php', 'status' => 'M', 'additions' => 18, 'deletions' => 6, 'findings' => 2, 'level' => 'risk'],
        ['path' => 'app/Models/Scope.php', 'status' => 'M', 'additions' => 9, 'deletions' => 3, 'findings' => 1],
        ['path' => 'app/Billing/Invoice.php', 'status' => 'A', 'additions' => 64],
        ['path' => 'app/Livewire/MilestoneBoard.php', 'status' => 'M', 'additions' => 22, 'deletions' => 11, 'findings' => 1],
        ['path' => 'app/Livewire/LegacyReport.php', 'status' => 'D', 'deletions' => 48],
        ['path' => 'tests/Feature/MilestoneTest.php', 'status' => 'A', 'additions' => 41],
        ['path' => 'database/migrations/2026_10_14_add_due_on.php', 'status' => 'A', 'additions' => 12],
    ];
@endphp

<x-ui::file-tree
    value="app/Models/Milestone.php"
    class="h-[460px] w-[320px] rounded-2xl border border-line"
    :files="$files"
/>
