<?php

use Illuminate\Support\Facades\Blade;

test('accordion renders items with accessible triggers and regions', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::accordion>
            <x-ui::accordion.item heading="A IA guarda meu código?">Não. Ela lê o diff e descarta.</x-ui::accordion.item>
        </x-ui::accordion>
        BLADE);

    expect($html)
        ->toContain('data-accordion')
        ->toContain('x-data="uiAccordion({ exclusive: false })"')
        ->toContain('<h3')
        ->toContain('data-accordion-trigger')
        ->toContain('aria-expanded="false"')
        ->toContain('role="region"')
        ->toContain('hidden="until-found"')
        ->toContain('x-on:beforematch')
        ->toContain('A IA guarda meu código?')
        ->toContain('Não. Ela lê o diff e descarta.')
        ->toContain('data-icon="arrow-down-01"');
});

test('exclusive accordion tells alpine to keep one item open', function () {
    $html = Blade::render('<x-ui::accordion exclusive><x-ui::accordion.item heading="Um">1</x-ui::accordion.item></x-ui::accordion>');

    expect($html)->toContain('uiAccordion({ exclusive: true })');
});

test('expanded item starts open and visible', function () {
    $html = Blade::render('<x-ui::accordion><x-ui::accordion.item heading="Aberto" expanded>Conteúdo</x-ui::accordion.item></x-ui::accordion>');

    expect($html)
        ->toContain('uiAccordionItem({ expanded: true, disabled: false })')
        ->toContain('aria-expanded="true"')
        ->toContain('data-open="true"')
        ->not->toContain('hidden="until-found"');
});

test('disabled item disables its trigger', function () {
    $html = Blade::render('<x-ui::accordion><x-ui::accordion.item heading="Bloqueado" disabled>Conteúdo</x-ui::accordion.item></x-ui::accordion>');

    expect($html)
        ->toContain('uiAccordionItem({ expanded: false, disabled: true })')
        ->toMatch('/data-accordion-trigger[^>]*\sdisabled[\s>]/');
});

test('transition animates the content with x-collapse', function () {
    $html = Blade::render('<x-ui::accordion transition><x-ui::accordion.item heading="Um">1</x-ui::accordion.item></x-ui::accordion>');

    expect($html)->toContain('x-show="open"')->toContain('x-collapse');
});

test('reverse variants put the chevron on the left', function (string $variant) {
    $html = Blade::render(
        '<x-ui::accordion :variant="$variant"><x-ui::accordion.item heading="Um">1</x-ui::accordion.item></x-ui::accordion>',
        ['variant' => $variant],
    );

    expect($html)
        ->toContain('data-icon="arrow-right-01"')
        ->not->toContain('data-icon="arrow-down-01"');
})->with(['reverse', 'group', 'cards']);

test('group and cards variants apply their containers', function () {
    $group = Blade::render('<x-ui::accordion variant="group"><x-ui::accordion.item heading="Um">1</x-ui::accordion.item></x-ui::accordion>');
    $cards = Blade::render('<x-ui::accordion variant="cards"><x-ui::accordion.item heading="Um">1</x-ui::accordion.item></x-ui::accordion>');

    expect($group)->toContain('rounded-[14px] border border-line bg-card');
    expect($cards)->toContain('data-[open=true]:border-line-hover');
});

test('heading and content can be composed with a trailing slot outside the trigger', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::accordion variant="cards">
            <x-ui::accordion.item>
                <x-ui::accordion.heading>
                    M3 · Financeiro
                    <x-slot:trailing>
                        <x-ui::tag color="risk">Em risco</x-ui::tag>
                    </x-slot:trailing>
                </x-ui::accordion.heading>
                <x-ui::accordion.content>Lançamento de dízimos</x-ui::accordion.content>
            </x-ui::accordion.item>
        </x-ui::accordion>
        BLADE);

    $trigger = str($html)->between('data-accordion-trigger', '</button>');

    expect($html)->toContain('M3 · Financeiro')->toContain('Em risco')->toContain('Lançamento de dízimos');
    expect((string) $trigger)->toContain('M3 · Financeiro')->not->toContain('Em risco');
});
