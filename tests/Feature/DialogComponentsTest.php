<?php

use Illuminate\Support\Facades\Blade;

/**
 * Devolve a tag de abertura do botão cujo texto visível é o informado.
 */
function buttonTagFor(string $html, string $label): string
{
    foreach (array_slice(explode('<button', $html), 1) as $chunk) {
        $tag = str($chunk)->before('>')->value();
        $text = trim(strip_tags(str($chunk)->after('>')->before('</button>')->value()));

        if ($text === $label) {
            return '<button'.$tag;
        }
    }

    return '';
}

test('confirm dialog renders a modal with icon tile, heading, text and both actions', function () {
    $html = Blade::render('<x-ui::confirm-dialog name="publicar" color="ai" icon="git-pull-request" heading="Publicar review?" text="2 comentários vão para o PR." confirm="Publicar" />');

    expect($html)
        ->toContain("x-data=\"uiModal({ name: 'publicar'")
        ->toContain('w-[400px]')
        ->not->toContain('aria-label="Fechar"')
        ->toContain('border-warn-line bg-warn-bg text-accent')
        ->toContain('data-icon="git-pull-request"')
        ->toContain('<h2 class="text-lg font-semibold">Publicar review?</h2>')
        ->toContain('2 comentários vão para o PR.')
        ->toContain('Cancelar')
        ->toContain('Publicar');
    expect($html)->toMatch('/'.preg_quote(buttonTagFor($html, 'Cancelar'), '/').'.*'.preg_quote(buttonTagFor($html, 'Publicar'), '/').'/s');
});

test('confirm dialog forwards confirm attributes to the confirm button only', function () {
    $html = Blade::render('<x-ui::confirm-dialog name="publicar" heading="Publicar?" confirm="Publicar" confirm:wire:click="publish" wire:model="showConfirm" />');

    expect(buttonTagFor($html, 'Publicar'))->toContain('wire:click="publish"');
    expect(buttonTagFor($html, 'Cancelar'))->not->toContain('wire:click');
    expect($html)
        ->toMatch('/data-modal\b[^>]*wire:model="showConfirm"/s')
        ->not->toContain('confirm:wire:click');
});

test('confirm dialog picks a default icon per color', function (string $color, string $icon) {
    expect(Blade::render('<x-ui::confirm-dialog :color="$color" heading="?" />', ['color' => $color]))
        ->toContain("data-icon=\"{$icon}\"");
})->with([
    ['default', 'information-circle'],
    ['ai', 'sparkles'],
    ['success', 'checkmark-circle-02'],
]);

test('confirm dialog closes on confirm unless told otherwise', function () {
    $closing = Blade::render('<x-ui::confirm-dialog heading="?" confirm="Ok" />');
    $staying = Blade::render('<x-ui::confirm-dialog heading="?" confirm="Ok" :close-on-confirm="false" />');

    expect(substr_count($closing, 'data-modal-close'))->toBe(2);
    expect(substr_count($staying, 'data-modal-close'))->toBe(1);
});

test('danger dialog asks to type the resource before enabling the destructive button', function () {
    $html = Blade::render('<x-ui::danger-dialog name="parar" heading="Parar de monitorar sge-api?" resource="sge-api" confirm="Parar de monitorar" confirm:wire:click="stop" />');

    $confirmButton = buttonTagFor($html, 'Parar de monitorar');

    expect($html)
        ->toContain('border-risk-line bg-risk-bg text-risk-fg')
        ->toContain('data-icon="alert-diamond"')
        ->toContain('Digite <code')
        ->toContain('>sge-api</code> para confirmar')
        ->toContain('x-model="typed"')
        ->toContain('x-bind:aria-invalid')
        ->toMatch('/x-data="\{ typed: &#039;&#039;, resource: &#039;sge-api&#039; \}"/')
        ->toContain('$watch(&#039;open&#039;');

    expect($confirmButton)
        ->toContain('bg-missing text-white')
        ->toContain('wire:click="stop"')
        ->toContain('x-bind:disabled="typed !== resource"')
        ->toMatch('/\sdisabled[\s>]/');
});

test('danger dialog without resource is a simple red confirmation', function () {
    $html = Blade::render('<x-ui::danger-dialog heading="Descartar?" confirm="Descartar" />');

    expect($html)->not->toContain('x-model="typed"');
    expect(buttonTagFor($html, 'Descartar'))
        ->toContain('bg-missing')
        ->not->toContain('x-bind:disabled');
});

test('danger dialog prompt can be customized', function () {
    $html = Blade::render('<x-ui::danger-dialog heading="Delete?" resource="sge-api" prompt="Type :resource to confirm" />');

    expect($html)->toContain('Type <code')->toContain('>sge-api</code> to confirm');
});

test('button has a solid destructive variant', function () {
    expect(Blade::render('<x-ui::button variant="destructive">Excluir</x-ui::button>'))
        ->toContain('border-missing bg-missing text-white');
});
