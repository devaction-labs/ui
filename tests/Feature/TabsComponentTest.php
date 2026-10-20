<?php

use Illuminate\Support\Facades\Blade;

test('tab group wires tabs and panels with alpine and aria roles', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::tab.group>
            <x-ui::tabs>
                <x-ui::tab name="geral" selected>Visão geral</x-ui::tab>
                <x-ui::tab name="contrato">Contrato</x-ui::tab>
            </x-ui::tabs>
            <x-ui::tab.panel name="geral" selected>Painel geral</x-ui::tab.panel>
            <x-ui::tab.panel name="contrato">Painel contrato</x-ui::tab.panel>
        </x-ui::tab.group>
        BLADE);

    expect($html)
        ->toContain('x-data="uiTabGroup"')
        ->toContain('x-on:ui-tab-change.stop="selected = $event.detail"')
        ->toContain('role="tablist"')
        ->toContain('x-data="uiTabs"')
        ->toContain('x-modelable="selected"')
        ->toMatch('/data-tab-name="geral"\s+data-selected\s+aria-selected="true"/')
        ->toMatch('/data-tab-name="contrato"\s+aria-selected="false"/')
        ->toContain('role="tabpanel"')
        ->toContain("x-show=\"selected === 'geral'\"")
        ->toContain("x-show=\"selected === 'contrato'\"");
    expect(substr_count($html, 'x-cloak'))->toBe(1);
});

test('tabs render each variant style', function (string $variant, string $listClass, string $selectedClass) {
    $html = Blade::render('<x-ui::tabs :variant="$variant"><x-ui::tab>Um</x-ui::tab></x-ui::tabs>', ['variant' => $variant]);

    expect($html)->toContain($listClass)->toContain($selectedClass);
})->with([
    ['default', 'gap-1 border-b border-line', 'aria-selected:border-ink'],
    ['segmented', 'rounded-xl border border-line bg-wrap p-1', 'aria-selected:bg-card'],
    ['pills', 'gap-1', 'aria-selected:bg-ink'],
]);

test('small tabs shrink the height', function () {
    expect(Blade::render('<x-ui::tabs variant="segmented" size="sm"><x-ui::tab>Um</x-ui::tab></x-ui::tabs>'))
        ->toContain('h-[30px] px-2.5 text-[13px]');
});

test('tab supports icons, badge with color and disabled', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::tabs>
            <x-ui::tab icon="git-pull-request" icon:trailing="arrow-right-01" badge="4" badge:color="red">PRs</x-ui::tab>
            <x-ui::tab disabled>Arquivados</x-ui::tab>
        </x-ui::tabs>
        BLADE);

    expect($html)
        ->toContain('data-icon="git-pull-request"')
        ->toContain('data-icon="arrow-right-01"')
        ->toContain('text-risk-fg">4</span>')
        ->toMatch('/data-tab\b[^>]*\sdisabled[\s>]/')
        ->not->toContain('badge:color')
        ->not->toContain('icon:trailing');
});

test('tabs forward wire:model and can scroll', function () {
    $html = Blade::render('<x-ui::tabs wire:model.live="view" scrollable><x-ui::tab name="lista">Lista</x-ui::tab></x-ui::tabs>');

    expect($html)
        ->toContain('wire:model.live="view"')
        ->toContain('overflow-x-auto');
});
