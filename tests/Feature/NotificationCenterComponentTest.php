<?php

use Illuminate\Support\Facades\Blade;

test('notification center renders tabs, day groups and unread items', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::notification-center :tabs="['all' => 'Tudo', 'mention' => 'Menções']" mark-all="markAllAsRead">
            <x-ui::notification-center.group heading="Hoje">
                <x-ui::notification-center.item type="mention" icon="at" color="info" unread meta="há 1 h" href="/tarefas/1">Carlos mencionou você</x-ui::notification-center.item>
                <x-ui::notification-center.item type="review" icon="sparkles" color="warn" wire:click="open(2)">Review pronto</x-ui::notification-center.item>
            </x-ui::notification-center.group>
            <x-slot:footer><a href="/notificacoes">Ver todas</a></x-slot:footer>
        </x-ui::notification-center>
        BLADE);

    expect($html)
        ->toContain('data-notification-center')
        ->toContain("markAll: 'markAllAsRead'")
        ->toContain('role="tab"')
        ->toContain('Menções')
        ->toContain('Hoje')
        ->toContain('data-type="mention"')
        ->toContain('data-unread="true"')
        ->toContain('data-unread="false"')
        ->toContain('href="/tarefas/1"')
        ->toContain('bg-info-bg text-info-fg')
        ->toContain('wire:click="open(2)"')
        ->toContain('Marcar tudo como lido')
        ->toContain('Ver todas')
        ->not->toContain('role="listitem"');
});
