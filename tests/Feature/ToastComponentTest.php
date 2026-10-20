<?php

use DevAction\Ui\Livewire\InteractsWithToasts;
use Illuminate\Support\Facades\Blade;

test('toast group renders a fixed stack wired to alpine', function () {
    $html = Blade::render('<x-ui::toast.group />');

    expect($html)
        ->toContain('data-toast-group')
        ->toContain('x-data="uiToastGroup({ max: 12, toasts: [] })"')
        ->toContain('x-on:mouseenter="expand()"')
        ->toContain('x-on:keydown.alt.t.window.prevent="focusStack()"')
        ->toContain('fixed z-[60] w-[360px]')
        ->toContain('bottom-4')
        ->toContain('right-4')
        ->toContain('x-for="(toast, index) in toasts"')
        ->toContain("x-bind:role=\"toast.variant === 'danger' ? 'alert' : 'status'\"")
        ->toContain('x-on:keydown.escape.stop="dismiss(toast.id)"')
        ->toContain('aria-label="Fechar notificação"');
});

test('toast group position moves the stack', function (string $position, array $expected) {
    $html = Blade::render('<x-ui::toast.group :position="$position" />', ['position' => $position]);

    foreach ($expected as $class) {
        expect($html)->toContain($class);
    }
})->with([
    ['top end', ['top-4', 'right-4', 'top-0 origin-top']],
    ['bottom start', ['bottom-4', 'left-4']],
    ['top center', ['top-4', 'left-1/2 -translate-x-1/2']],
]);

test('toast group accepts initial toasts', function () {
    $html = Blade::render('<x-ui::toast.group :toasts="$toasts" />', [
        'toasts' => [['text' => 'Contrato salvo.', 'variant' => 'success']],
    ]);

    expect($html)->toContain('Contrato salvo.')->toContain('success');
});

test('livewire trait dispatches toast events without empty fields', function () {
    $component = new class
    {
        use InteractsWithToasts;

        /** @var list<array<int|string, mixed>> */
        public array $dispatched = [];

        public function dispatch(string $event, mixed ...$params): void
        {
            $this->dispatched[] = ['event' => $event, ...$params];
        }
    };

    $component->toast('Alterações salvas.', variant: 'success');
    $component->toast('Tarefa excluída.', action: ['label' => 'Desfazer', 'event' => 'undo-delete'], id: 'tarefa-9');
    $component->dismissToast('tarefa-9');

    expect($component->dispatched)->toBe([
        ['event' => 'ui-toast', 'text' => 'Alterações salvas.', 'variant' => 'success'],
        ['event' => 'ui-toast', 'text' => 'Tarefa excluída.', 'variant' => 'default', 'id' => 'tarefa-9', 'action' => ['label' => 'Desfazer', 'event' => 'undo-delete']],
        ['event' => 'ui-toast-dismiss', 'id' => 'tarefa-9'],
    ]);
});

test('toast group renders the progress toast fill, tile and percentage', function () {
    $html = Blade::render('<x-ui::toast.group />');

    expect($html)
        ->toContain("toast.progress >= 100 ? 'bg-ok-fg/15' : 'bg-ai/15'")
        ->toContain("x-text=\"Math.min(100, Math.round(toast.progress)) + '%'\"")
        ->toContain('role="progressbar"');
});

test('static progress toast fills the background and completes with a check', function () {
    $running = Blade::render('<x-ui::toast.progress :value="64" heading="Sincronizando" text="4 de 6" />');
    $done = Blade::render('<x-ui::toast.progress :value="120" heading="Pronto" />');

    expect($running)
        ->toContain('style="width: 64%"')
        ->toContain('bg-ai/15')
        ->toContain('data-spinner')
        ->toContain('aria-valuenow="64"')
        ->toContain('>64%</span>');

    expect($done)
        ->toContain('style="width: 100%"')
        ->toContain('border-ok-line')
        ->toContain('data-icon="tick-02"')
        ->not->toContain('data-spinner');
});

test('livewire progress toast dispatches a persistent toast with progress', function () {
    $component = new class
    {
        use InteractsWithToasts;

        /** @var list<array<int|string, mixed>> */
        public array $dispatched = [];

        public function dispatch(string $event, mixed ...$params): void
        {
            $this->dispatched[] = ['event' => $event, ...$params];
        }
    };

    $component->progressToast('importacao', 40, 'Importando…', heading: 'Contatos');

    expect($component->dispatched[0])->toBe([
        'event' => 'ui-toast', 'text' => 'Importando…', 'heading' => 'Contatos', 'variant' => 'default', 'duration' => 0, 'id' => 'importacao', 'progress' => 40,
    ]);
});
