<?php

use DevAction\Ui\Mcp\MarkupBuilder;
use DevAction\Ui\Support\ComponentManifest;
use Illuminate\Support\Facades\Blade;

it('B1 monta botão com slot', function () {
    expect((new MarkupBuilder)->build('button', ['variant' => 'primary'], 'Save'))
        ->toBe('<x-ui::button variant="primary">Save</x-ui::button>');
});

it('B2 autofecha sem slot e mantém a ordem', function () {
    expect((new MarkupBuilder)->build('button', ['icon' => 'add-01', 'aria-label' => 'Add']))
        ->toBe('<x-ui::button icon="add-01" aria-label="Add" />');
});

it('B3 true vira atributo nu', function () {
    expect((new MarkupBuilder((new ComponentManifest)->load()))->build('modal', ['name' => 'edit', 'flyout' => true], 'Body'))
        ->toBe('<x-ui::modal name="edit" flyout>Body</x-ui::modal>');
});

it('B4 false vira :prop="false"', function () {
    expect((new MarkupBuilder((new ComponentManifest)->load()))->build('modal', ['dismissible' => false]))->toContain(':dismissible="false"');
});

it('B5 número vira :prop', function () {
    expect((new MarkupBuilder)->build('number-input', ['min' => 0, 'max' => 10]))->toContain(':min="0" :max="10"');
});

it('B6 string com $ vira expressão', function () {
    expect((new MarkupBuilder)->build('table', ['paginate' => '$this->orders']))->toContain(':paginate="$this->orders"');
});

it('B7 camelCase vira kebab', function () {
    expect((new MarkupBuilder)->build('button', ['iconTrailing' => 'arrow-right-01']))->toContain('icon-trailing="arrow-right-01"');
});

it('B8 mantém passthrough na ordem', function () {
    expect((new MarkupBuilder)->build('button', ['wire:click' => 'save', 'class' => 'w-full']))
        ->toBe('<x-ui::button wire:click="save" class="w-full" />');
});

it('B9 recusa prop desconhecida listando as válidas', function () {
    (new MarkupBuilder)->build('button', ['varient' => 'primary']);
})->throws(InvalidArgumentException::class, 'Unknown prop "varient" for x-ui::button. Valid: variant');

it('B10 slots nomeados vêm antes do padrão, indentados, e compilam', function () {
    $markup = (new MarkupBuilder)->build('panel', [], 'Body', ['actions' => '<x-ui::button>Edit</x-ui::button>']);

    expect($markup)->toBe("<x-ui::panel>\n    <x-slot:actions><x-ui::button>Edit</x-ui::button></x-slot:actions>\n    Body\n</x-ui::panel>");
    expect(Blade::compileString($markup))->toContain('slot(\'actions\'');
});

it('escapa aspas no valor', function () {
    expect((new MarkupBuilder)->build('button', ['title' => 'a "b"']))->toContain('title="a &quot;b&quot;"');
});

it('recusa componente desconhecido', function () {
    (new MarkupBuilder)->build('nope');
})->throws(InvalidArgumentException::class, 'Unknown component "nope"');

it('recusa chave de atributo maliciosa', function (string $key) {
    expect(fn () => (new MarkupBuilder)->build('button', [$key => 'x']))
        ->toThrow(InvalidArgumentException::class, 'Invalid attribute name');
})->with(['wire:x"onmouseover="alert(1)', "@php(system('id'))", 'x-on:click"><script>']);

it('aceita chaves passthrough válidas', function (string $key) {
    expect((new MarkupBuilder)->build('button', [$key => 'x']))->toContain($key.'="x"');
})->with(['wire:model.live', 'x-on:click', 'aria-label', 'data-id']);

it('B17 normaliza chaves @evento para x-on:evento', function (string $key, string $expected) {
    expect((new MarkupBuilder)->build('button', [$key => 'go()']))->toBe('<x-ui::button '.$expected.'="go()" />');
})->with([['@click', 'x-on:click'], ['@keydown.enter', 'x-on:keydown.enter'], ['@php', 'x-on:php']]);

it('S14 recusa quebra de linha final em atributo e slot', function () {
    expect(fn () => (new MarkupBuilder)->build('button', ["wire:x\n" => 'a']))->toThrow(InvalidArgumentException::class, 'Invalid attribute name');
    expect(fn () => (new MarkupBuilder)->build('panel', [], null, ["a\n" => 'x']))->toThrow(InvalidArgumentException::class, 'Invalid slot name');
});

it('S15 recusa expressão ligada com aspas duplas', function () {
    (new MarkupBuilder)->build('button', ['title' => '$a" onmouseover="alert(1)']);
})->throws(InvalidArgumentException::class, 'Bound expression for "title" cannot contain double quotes.');

it('S13 neutraliza Blade em valores string', function (string $value) {
    $markup = (new MarkupBuilder)->build('button', ['title' => $value]);

    $baseline = Blade::compileString((new MarkupBuilder)->build('button', ['title' => 'x']));

    expect(substr_count(Blade::compileString($markup), '<?php'))->toBe(substr_count($baseline, '<?php'));
})->with(['a{{ phpinfo() }}b', '{!! x !!}', "@php(system('id'))", '{{-- --}}']);

it('B16 escapa & e @ preservando o texto renderizado', function () {
    expect((new MarkupBuilder)->build('button', ['title' => 'Save & close']))->toContain('title="Save &amp; close"');
    expect((new MarkupBuilder)->build('button', ['title' => 'ana@acme.com']))->toContain('title="ana&#64;acme.com"');
});

it('recusa nome de slot malicioso', function () {
    (new MarkupBuilder)->build('panel', [], null, ['a><script>x</script><x-slot:b' => 'x']);
})->throws(InvalidArgumentException::class, 'Invalid slot name');
