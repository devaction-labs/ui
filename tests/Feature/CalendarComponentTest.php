<?php

use Illuminate\Support\Facades\Blade;

test('calendar month view renders a monday-first grid with events', function () {
    $html = Blade::render('<x-ui::calendar month="2026-10" today="2026-10-15" selected="2026-10-16" :events="$events" :legend="[\'blue\' => \'Entrega\']" />', ['events' => [
        ['date' => '2026-10-16', 'title' => 'Entrega M3', 'color' => 'blue'],
        ['date' => '2026-10-16', 'title' => 'Review', 'color' => 'teal'],
    ]]);

    expect($html)
        ->toContain('data-view="month"')
        ->toContain('Outubro 2026')
        ->toContain('role="grid"')
        ->toContain('>seg</span>')
        ->toContain('data-date="2026-09-28"')
        ->toContain('data-date="2026-11-01"')
        ->toMatch('/data-date="2026-10-16"\s+aria-selected="true"/')
        ->toContain('16 de outubro, 2 eventos')
        ->toContain('Entrega M3')
        ->toContain('bg-info-bg')
        ->toContain('calendar-day')
        ->toContain('bg-ink font-semibold text-on-ink')
        ->toContain('month=2026-11')
        ->toMatch('/>Entrega\s*<\/span>/');
});

test('calendar week view positions timed events on the hour grid', function () {
    $html = Blade::render('<x-ui::calendar month="2026-10" view="week" today="2026-10-15" :events="$events" />', ['events' => [
        ['date' => '2026-10-13', 'title' => 'PR #142', 'start' => '11:00', 'end' => '12:30'],
    ]]);

    expect($html)
        ->toContain('data-view="week"')
        ->toContain('repeat(5, minmax(0, 1fr))')
        ->toContain('08h')
        ->toContain('top: 170px; height: 80px')
        ->toContain('PR #142');
});

test('calendar uses livewire properties for navigation when given', function () {
    $html = Blade::render('<x-ui::calendar month="2026-10" month-property="month" view-property="view" />');

    expect($html)
        ->toContain('wire:click="$set(&#039;month&#039;, &#039;2026-09&#039;); $set(&#039;view&#039;, &#039;month&#039;)"')
        ->not->toContain('href=');
});
