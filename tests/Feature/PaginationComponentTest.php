<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;

test('pagination renders numbers with ellipsis and the current page', function () {
    $paginator = new LengthAwarePaginator(range(1, 10), 90, 10, 2, ['path' => '/contatos']);

    $html = Blade::render('<x-ui::pagination :paginator="$paginator" />', ['paginator' => $paginator]);

    expect($html)
        ->toContain('aria-label="Paginação"')
        ->toContain('Mostrando')
        ->toContain('11–20')
        ->toContain('>90</span>')
        ->toMatch('/aria-current="page">2<\/span>/')
        ->toContain('href="/contatos?page=1"')
        ->toContain('href="/contatos?page=3"')
        ->toContain('href="/contatos?page=9"')
        ->toContain('…')
        ->not->toContain('href="/contatos?page=5"')
        ->toContain('aria-label="Anterior"')
        ->toContain('aria-label="Próxima"');
});

test('pagination uses livewire methods when asked', function () {
    $paginator = new LengthAwarePaginator(range(1, 10), 30, 10, 1);

    $html = Blade::render('<x-ui::pagination :paginator="$paginator" livewire />', ['paginator' => $paginator]);

    expect($html)
        ->toContain("wire:click=\"setPage(2, 'page')\"")
        ->toContain("wire:click=\"nextPage('page')\"")
        ->toMatch('/aria-disabled="true" aria-label="Anterior"/');
});

test('simple pagination shows only previous and next', function () {
    $paginator = new Paginator(range(1, 11), 10, 3, ['path' => '/lista']);

    $html = Blade::render('<x-ui::pagination :paginator="$paginator" />', ['paginator' => $paginator]);

    expect($html)
        ->toContain('href="/lista?page=2"')
        ->toContain('href="/lista?page=4"')
        ->toContain('Página <span')
        ->not->toContain('aria-current');
});

test('pagination is hidden with a single page', function () {
    $paginator = new LengthAwarePaginator(range(1, 3), 3, 10, 1);

    expect(trim(Blade::render('<x-ui::pagination :paginator="$paginator" />', ['paginator' => $paginator])))->toBe('');
});
