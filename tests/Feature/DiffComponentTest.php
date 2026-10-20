<?php

use Illuminate\Support\Facades\Blade;

test('diff parses a unified patch into numbered add, delete and context lines', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::diff path="app/Models/Milestone.php" comment="addComment" patch="@@ -12,3 +12,3 @@ class Milestone
         class Milestone
        -    old line
        +    new line
         }" />
        BLADE);

    expect($html)
        ->toContain('data-diff')
        ->toContain("uiDiff({ comment: 'addComment' })")
        ->toContain('app/Models/Milestone.php')
        ->toContain('+1</span> <span class="text-risk-fg">−1')
        ->toContain('@@ -12,3 +12,3 @@ class Milestone')
        ->toContain('data-diff-line="13"')
        ->toContain('bg-ok-bg text-ok-fg')
        ->toContain('bg-risk-bg text-risk-fg')
        ->toContain('aria-label="Comentar na linha 13"')
        ->toContain('--diff-line-13: ');
});

test('diff threads sit under their line with comments and suggestions', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::diff :lines="[['type' => 'ctx', 'old' => 18, 'new' => 18, 'code' => 'return [];']]">
            <x-ui::diff.thread line="18" resolve="resolveThread(7)">
                <x-ui::diff.comment author="Revisor" ai when="agora">Falta a migração.</x-ui::diff.comment>
                <x-ui::diff.comment author="Alex" suggestion-from="a" suggestion-to="b" apply="applySuggestion(3)">Sugestão</x-ui::diff.comment>
            </x-ui::diff.thread>
        </x-ui::diff>
        BLADE);

    expect($html)
        ->toContain('data-diff-thread="18"')
        ->toContain('order: var(--diff-line-18, 99999)')
        ->toContain('--diff-line-18: 1')
        ->toContain('Falta a migração.')
        ->toContain('>IA</span>')
        ->toContain('Aplicar sugestão')
        ->toContain('wire:click="applySuggestion(3)"')
        ->toContain('wire:click="resolveThread(7)"')
        ->toContain('Resolver');
});
