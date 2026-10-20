<?php

use Illuminate\Support\Facades\Blade;

test('activity renders comments, events, the ai review and the composer on one rail', function () {
    $html = Blade::render('<x-ui::activity composer-name="Alex">
<x-ui::activity.comment name="Carlos André" when="há 2 dias" role="Autor" edited>
Duas transações.
<x-slot:footer>
<x-ui::reactions :reactions="[\'🚀\' => 1]" />
</x-slot:footer>
</x-ui::activity.comment>
<x-ui::activity.event icon="tag-01" color="risk" name="Alex" when="ontem">adicionou um rótulo</x-ui::activity.event>
<x-ui::activity.comment variant="ai" name="DevAction IA" verb="revisou o PR #142" when="há 2 h">Achados</x-ui::activity.comment>
<x-slot:composer>
<textarea></textarea>
</x-slot:composer>
</x-ui::activity>');

    expect($html)
        ->toContain('data-activity')
        ->toContain('aria-label="Atividade"')
        ->toContain('comentou há 2 dias')
        ->toContain('· editado')
        ->toContain('Autor')
        ->toContain('data-activity-event')
        ->toContain('bg-risk-bg text-risk-fg')
        ->toContain('revisou o PR #142 há 2 h')
        ->toContain('border-warn-line')
        ->toContain('data-reactions')
        ->toContain('<textarea>');

    expect(substr_count($html, 'data-activity-comment'))->toBe(2);
});

test('checklist counts checked items, shows progress and forwards wire:model to the checkbox', function () {
    $html = Blade::render('<x-ui::checklist heading="Pronto para merge">
<x-ui::checklist.item checked description="Carlos">Multi</x-ui::checklist.item>
<x-ui::checklist.item wire:model.live="checks.recibo">Recibo</x-ui::checklist.item>
</x-ui::checklist>');

    expect($html)
        ->toContain('data-checklist')
        ->toContain('Pronto para merge')
        ->toContain('role="progressbar"')
        ->toContain('Esconder itens marcados')
        ->toContain('wire:model.live="checks.recibo"')
        ->toContain('checked')
        ->toContain('Carlos');

    expect(Blade::render('<x-ui::checklist><x-ui::checklist.item>Solto</x-ui::checklist.item></x-ui::checklist>'))
        ->not->toContain('role="progressbar"');
});

test('properties renders sections with label and value rows', function () {
    $html = Blade::render('<x-ui::properties variant="card">
<x-ui::properties.section heading="Quadro">
<x-slot:action>
<button>Editar</button>
</x-slot:action>
<x-ui::properties.item label="Estimativa" mono>5 pts</x-ui::properties.item>
</x-ui::properties.section>
</x-ui::properties>');

    expect($html)
        ->toContain('aria-label="Detalhes"')
        ->toContain('data-properties-section')
        ->toContain('Quadro')
        ->toContain('<button>Editar</button>')
        ->toContain('font-mono')
        ->toContain('5 pts');
});

test('reactions keep counts and your own reactions in Alpine and call Livewire when asked', function () {
    $html = Blade::render('<x-ui::reactions :reactions="[\'👍\' => 2]" :selected="[\'👍\']" react="toggleReaction" />');

    expect($html)
        ->toContain('data-reactions')
        ->toContain('reaction-toggled')
        ->toContain("this.\$wire?.call('toggleReaction', emoji)")
        ->toContain('aria-label="Adicionar reação"')
        ->toContain('🎉');
});
