<?php

use Illuminate\Support\Facades\Blade;

test('editor renders a toolbar and a contenteditable area', function () {
    $html = Blade::render('<x-ui::editor label="Notas" wire:model="notes" value="<p>Oi</p>" />');

    expect($html)
        ->toContain('data-editor')
        ->toContain('uiEditor({ value: ')
        ->toContain('x-modelable="value"')
        ->toContain('wire:model="notes"')
        ->toContain('role="toolbar"')
        ->toContain('aria-label="Estilo do texto"')
        ->toContain('aria-label="Negrito (⌘B)"')
        ->toContain('aria-label="Lista numerada"')
        ->toContain('aria-label="Refazer (⇧⌘Z)"')
        ->toContain('contenteditable="true"')
        ->toContain('role="textbox"')
        ->toContain('id="ui-editor-notes"')
        ->toContain('data-slash-item')
        ->toContain('aria-label="Comandos"');
});

test('editor toolbar can be customized', function () {
    $html = Blade::render('<x-ui::editor toolbar="bold | link" />');

    expect($html)
        ->toContain('aria-label="Negrito (⌘B)"')
        ->toContain('aria-label="Link"')
        ->not->toContain('aria-label="Itálico (⌘I)"')
        ->not->toContain('aria-label="Estilo do texto"');
    expect(substr_count($html, 'mx-1 h-[18px] w-px'))->toBe(1);
});

test('editor renders actions slot, hidden input and errors', function () {
    $html = Blade::render('<x-ui::editor name="notes" error="Escreva as notas"><x-slot:actions><button>IA</button></x-slot:actions></x-ui::editor>');

    expect($html)
        ->toContain('<button>IA</button>')
        ->toContain('name="notes"')
        ->toContain('aria-invalid="true"')
        ->toContain('Escreva as notas');
});

test('editor offers @ mentions and custom slash commands', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::editor mention-search="mentionQuery"
            :mentions="[['value' => 'alex', 'label' => 'Alex', 'description' => 'Dev · online agora']]"
            :commands="[['label' => '/tarefa', 'description' => 'Criar tarefa', 'icon' => 'task-01', 'name' => 'task']]" />
        BLADE);

    expect($html)
        ->toContain("mentionSearch: 'mentionQuery'")
        ->toContain('aria-label="Pessoas"')
        ->toContain('data-mention-item')
        ->toContain('data-value="alex"')
        ->toContain('data-search="alex dev online agora alex"')
        ->toContain('Dev · online agora')
        ->toContain('/tarefa')
        ->toContain('runCommand(&#039;task&#039;)')
        ->toContain('[&_[data-mention]]:bg-info-bg');

    expect(Blade::render('<x-ui::editor />'))->not->toContain('aria-label="Pessoas"');
});
