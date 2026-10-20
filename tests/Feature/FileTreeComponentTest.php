<?php

use Illuminate\Support\Facades\Blade;

test('file tree builds folders from paths with status, totals and findings', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::file-tree wire:model.live="file" :files="[
            ['path' => 'app/Models/Milestone.php', 'status' => 'M', 'additions' => 18, 'deletions' => 6, 'findings' => 2, 'level' => 'risk'],
            ['path' => 'app/Billing/Invoice.php', 'status' => 'A', 'additions' => 64],
            ['path' => 'tests/LegacyTest.php', 'status' => 'D', 'deletions' => 48],
        ]" />
        BLADE);

    expect($html)
        ->toContain('data-file-tree')
        ->toContain('wire:model.live="file"')
        ->toContain('role="tree"')
        ->toMatch('/data-path="app"\s+data-parents=""/')
        ->toContain('data-path="app/Models"')
        ->toContain('data-parents="app|app/Models"')
        ->toContain('>Milestone.php</span>')
        ->toContain('>app/</span>')
        ->toContain('+82')
        ->toContain('−54')
        ->toContain('bg-risk-fg')
        ->toContain('achados')
        ->toContain('aria-level="3"')
        ->toContain('line-through')
        ->toContain('Com achados');

    expect(substr_count($html, 'role="treeitem"'))->toBe(7);
});
