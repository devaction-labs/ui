<?php

use Illuminate\Support\Facades\Blade;

test('callout renders heading and text shorthands with an icon', function () {
    $html = Blade::render('<x-ui::callout icon="clock-01" heading="Marco vence sexta" text="3 itens sem PR." />');

    expect($html)
        ->toContain('data-callout')
        ->toContain('border-line bg-subtle')
        ->toContain('data-icon="clock-01"')
        ->toContain('Marco vence sexta')
        ->toContain('3 itens sem PR.')
        ->not->toContain('role=');
});

test('callout maps variants to status tokens', function (string $variant, string $expectedClass) {
    $html = Blade::render('<x-ui::callout :variant="$variant" heading="Aviso" />', ['variant' => $variant]);

    expect($html)->toContain($expectedClass);
})->with([
    ['success', 'bg-ok-bg text-ok-fg'],
    ['warning', 'bg-warn-bg text-warn-fg'],
    ['danger', 'bg-risk-bg text-risk-fg'],
    ['ai', 'bg-warn-bg text-warn-fg'],
    ['info', 'bg-info-bg text-info-fg'],
]);

test('callout renders actions below by default and inline when asked', function () {
    $template = '<x-ui::callout heading="Aviso" %s><x-slot:actions><x-ui::button size="sm">Agir</x-ui::button></x-slot:actions></x-ui::callout>';

    $stacked = Blade::render(sprintf($template, ''));
    $inline = Blade::render(sprintf($template, 'inline'));

    expect($stacked)->toContain('mt-2.5 flex flex-wrap gap-2')->toContain('Agir');
    expect($inline)->toContain('self-center')->not->toContain('mt-2.5 flex flex-wrap gap-2');
});

test('callout renders controls and composed heading, text and link', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::callout>
            <x-ui::callout.heading icon="file-edit">Termos atualizados</x-ui::callout.heading>
            <x-ui::callout.text>Leia os <x-ui::callout.link href="https://example.com" external>novos termos</x-ui::callout.link>.</x-ui::callout.text>
            <x-slot:controls><x-ui::button variant="ghost" icon="cancel-01" aria-label="Dispensar" /></x-slot:controls>
        </x-ui::callout>
        BLADE);

    expect($html)
        ->toContain('data-icon="file-edit"')
        ->toContain('target="_blank"')
        ->toContain('rel="noopener noreferrer"')
        ->toContain('aria-label="Dispensar"');
});

test('stacked callout shows the icon chip and mono label', function () {
    $html = Blade::render('<x-ui::callout stacked variant="ai" icon="sparkles" label="IA" heading="Resumo" text="Fora do M3." />');

    expect($html)
        ->toContain('flex-col gap-2.5 rounded-2xl')
        ->toContain('size-8 rounded-[9px] bg-current/10')
        ->toContain('font-mono')
        ->toContain('IA');
});

test('alert adds a default icon per variant and a status role', function (string $variant, string $icon, string $role) {
    $html = Blade::render('<x-ui::alert :variant="$variant" heading="Aviso" />', ['variant' => $variant]);

    expect($html)
        ->toContain("data-icon=\"{$icon}\"")
        ->toContain("role=\"{$role}\"");
})->with([
    ['secondary', 'information-circle', 'status'],
    ['success', 'checkmark-circle-02', 'status'],
    ['warning', 'alert-02', 'status'],
    ['ai', 'sparkles', 'status'],
    ['info', 'idea-01', 'status'],
    ['danger', 'cancel-circle', 'alert'],
]);

test('alert forwards actions to the callout', function () {
    $html = Blade::render('<x-ui::alert variant="danger" heading="Falhou">Tente de novo.<x-slot:actions><x-ui::button size="sm">Tentar agora</x-ui::button></x-slot:actions></x-ui::alert>');

    expect($html)->toContain('Tente de novo.')->toContain('Tentar agora');
});
