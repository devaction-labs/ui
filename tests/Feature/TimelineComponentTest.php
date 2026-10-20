<?php

use Illuminate\Support\Facades\Blade;

test('timeline renders an ordered list of items with dots, connectors and content', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::timeline>
            <x-ui::timeline.item>
                <x-ui::timeline.indicator color="amber" />
                <x-ui::timeline.content heading="IA revisou: 2 problemas" meta="09:14" />
            </x-ui::timeline.item>
        </x-ui::timeline>
        BLADE);

    expect($html)
        ->toContain('<ol')
        ->toContain('data-timeline')
        ->toContain('<li')
        ->toContain('grid grid-cols-[auto_minmax(0,1fr)] gap-x-3')
        ->toContain('bg-ai text-card size-2.5')
        ->toContain('aria-hidden="true" class="group-last/timeline-item:hidden bg-line my-1 w-px flex-1"')
        ->toContain('IA revisou: 2 problemas')
        ->toContain('09:14');
});

test('indicator colors map to tokens', function (string $color, string $expected) {
    expect(Blade::render('<x-ui::timeline.indicator :color="$color" />', ['color' => $color]))->toContain($expected);
})->with([
    ['red', 'bg-missing'],
    ['green', 'bg-ok-fg'],
    ['blue', 'bg-learning'],
    ['zinc', 'bg-muted'],
]);

test('indicator with content becomes a circle and lg grows it', function () {
    expect(Blade::render('<x-ui::timeline.indicator>2</x-ui::timeline.indicator>'))
        ->toContain('size-6 text-[11px]')
        ->toContain('border border-line bg-wrap');
    expect(Blade::render('<x-ui::timeline size="lg"><x-ui::timeline.item><x-ui::timeline.indicator>2</x-ui::timeline.indicator></x-ui::timeline.item></x-ui::timeline>'))
        ->toContain('size-8 text-xs');
});

test('item status styles the indicator and the connector', function (string $status, string $indicatorClass, string $connectorClass) {
    $html = Blade::render(
        '<x-ui::timeline><x-ui::timeline.item :status="$status"><x-ui::timeline.indicator>1</x-ui::timeline.indicator></x-ui::timeline.item></x-ui::timeline>',
        ['status' => $status],
    );

    expect($html)->toContain($indicatorClass)->toContain($connectorClass)->toContain("data-status=\"{$status}\"");
})->with([
    ['complete', 'bg-ink text-on-ink', 'hidden bg-ink'],
    ['current', 'ring-2 ring-ink', 'hidden bg-line'],
    ['incomplete', 'border border-line-strong bg-card text-muted', 'hidden bg-line'],
]);

test('bare indicator has no background or fixed size', function () {
    $html = Blade::render('<x-ui::timeline.indicator variant="bare"><x-ui::icon name="file-01" class="size-6" /></x-ui::timeline.indicator>');

    expect($html)->not->toContain('size-6 text-[11px]')->not->toContain('bg-wrap');
});

test('horizontal timeline lays items side by side', function () {
    $html = Blade::render('<x-ui::timeline horizontal><x-ui::timeline.item><x-ui::timeline.indicator /><x-ui::timeline.content heading="Contrato" /></x-ui::timeline.item></x-ui::timeline>');

    expect($html)
        ->toContain('flex w-full items-start')
        ->toContain('flex min-w-0 flex-1 flex-col gap-3')
        ->toContain('mx-2 h-px flex-1');
});

test('timeline block spans the full width', function () {
    expect(Blade::render('<x-ui::timeline.block>Comentário</x-ui::timeline.block>'))
        ->toContain('col-span-2')
        ->toContain('Comentário');
});
