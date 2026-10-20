<?php

use Illuminate\Support\Facades\Blade;

test('kanban columns wire up livewire sorting between groups', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::kanban>
            <x-ui::kanban.column id="review" heading="Em review" count="2" color="#D29A1E" limit="2" sort="moveTask">
                <x-ui::kanban.card key="5" due="ontem" overdue assignee="Bruno Lima">
                    Upload de comprovantes
                    <x-slot:tags><span>IA</span></x-slot:tags>
                </x-ui::kanban.card>
                <x-slot:footer><x-ui::kanban.add>Nova tarefa</x-ui::kanban.add></x-slot:footer>
            </x-ui::kanban.column>
        </x-ui::kanban>
        BLADE);

    expect($html)
        ->toContain('data-kanban')
        ->toContain('aria-label="Em review"')
        ->toContain('wire:sort="moveTask"')
        ->toContain('wire:sort:group="kanban"')
        ->toContain('wire:sort:group-id="review"')
        ->toContain('2/2 limite')
        ->toContain('text-risk-fg')
        ->toContain('wire:sort:item="5"')
        ->toContain('wire:key="kanban-card-5"')
        ->toContain('Upload de comprovantes')
        ->toContain('<span>IA</span>')
        ->toContain('ontem')
        ->toContain('wire:sort:ignore')
        ->toContain('Nova tarefa');
});

test('kanban column without sort is static and done cards are struck through', function () {
    $html = Blade::render('<x-ui::kanban.column heading="Feito"><x-ui::kanban.card done>Login</x-ui::kanban.card></x-ui::kanban.column>');

    expect($html)
        ->not->toContain('wire:sort=')
        ->toContain('line-through');
});

test('kanban column count can follow a wire:text expression', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::kanban.column id="review" heading="Em review" count="1" limit="2" count-text="$wire.totals.review.count" sort="moveTask" />
        BLADE);

    expect($html)
        ->toContain('wire:text="$wire.totals.review.count"')
        ->toContain("wire:text=\"(\$wire.totals.review.count) + '/2'")
        ->toContain("x-bind:class=\"{ 'text-risk-fg': (\$wire.totals.review.count) >= 2")
        ->toContain('1/2');
});
