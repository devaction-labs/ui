<?php

use Illuminate\Support\Facades\Blade;

test('breadcrumbs render an ordered list with links and the current page', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item href="/hoje">Hoje</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.item href="/sge" wire:navigate>SGE</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.item>PR #142</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
        BLADE);

    expect($html)
        ->toContain('aria-label="Trilha"')
        ->toContain('<ol')
        ->toContain('href="/hoje"')
        ->toMatch('/href="\/sge"[^>]*wire:navigate/s')
        ->toMatch('/aria-current="page"[^>]*>\s*<span class="truncate">PR #142/s')
        ->toContain('group-first/crumb:hidden');
    expect(substr_count($html, 'aria-current="page"'))->toBe(1);
    expect(substr_count($html, 'data-breadcrumbs-item'))->toBe(3);
});

test('breadcrumbs support chevron separators, small size and icon items', function () {
    $html = Blade::render('<x-ui::breadcrumbs separator="chevron" size="sm"><x-ui::breadcrumbs.item href="/" icon="home-01" aria-label="Início" /><x-ui::breadcrumbs.item>Aditivo</x-ui::breadcrumbs.item></x-ui::breadcrumbs>');

    expect($html)
        ->toContain('text-[13px]')
        ->toContain('data-icon="arrow-right-01"')
        ->toContain('data-icon="home-01"')
        ->toContain('aria-label="Início"');
});

test('icon only breadcrumb without link is not the current page', function () {
    $html = Blade::render('<x-ui::breadcrumbs><x-ui::breadcrumbs.item icon="more-horizontal" aria-label="Mais níveis" /><x-ui::breadcrumbs.item>Aditivo</x-ui::breadcrumbs.item></x-ui::breadcrumbs>');

    expect(substr_count($html, 'aria-current="page"'))->toBe(1);
});
