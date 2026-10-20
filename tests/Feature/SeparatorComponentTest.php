<?php

use Illuminate\Support\Facades\Blade;

test('separator renders a horizontal line, a labelled line and a vertical divider', function () {
    expect(Blade::render('<x-ui::separator />'))
        ->toContain('role="separator"')
        ->toContain('h-px w-full shrink-0 bg-line');

    expect(Blade::render('<x-ui::separator text="ou continue com" />'))
        ->toContain('ou continue com')
        ->toContain('h-px flex-1 bg-line');

    expect(Blade::render('<x-ui::separator vertical />'))
        ->toContain('aria-orientation="vertical"')
        ->toContain('w-px');

    expect(Blade::render('<x-ui::separator variant="subtle" />'))->toContain('bg-line-soft');
});
