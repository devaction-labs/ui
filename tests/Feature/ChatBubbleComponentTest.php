<?php

use Illuminate\Support\Facades\Blade;

test('chat renders a live log with user and ai bubbles', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::chat>
            <x-ui::chat.bubble from="user" time="14:02" status="sent">Resuma o PR</x-ui::chat.bubble>
            <x-ui::chat.bubble from="ai" name="DevAction IA" copyable feedback>Dois achados.</x-ui::chat.bubble>
        </x-ui::chat>
        BLADE);

    expect($html)
        ->toContain('role="log"')
        ->toContain('aria-live="polite"')
        ->toContain('data-from="user"')
        ->toContain('flex-row-reverse self-end')
        ->toContain('bg-ink px-4')
        ->toContain('Enviada')
        ->toContain('data-from="ai"')
        ->toContain('data-icon="sparkles"')
        ->toContain('DevAction IA')
        ->toContain('aria-label="Copiar resposta"')
        ->toContain('aria-label="Resposta útil"')
        ->toContain('chat-feedback')
        ->toContain('Dois achados.');
});

test('chat bubble typing, other sender and error status', function () {
    expect(Blade::render('<x-ui::chat.bubble from="ai" typing />'))
        ->toContain('IA está digitando')
        ->toContain('animate-blink')
        ->not->toContain('Copiar resposta');

    expect(Blade::render('<x-ui::chat.bubble from="other" name="Pr. Marcos">Oi</x-ui::chat.bubble>'))
        ->toContain('Pr. Marcos')
        ->toContain('self-start');

    expect(Blade::render('<x-ui::chat.bubble status="error">Oi</x-ui::chat.bubble>'))
        ->toContain('ring-missing')
        ->toContain('Não enviada');
});
