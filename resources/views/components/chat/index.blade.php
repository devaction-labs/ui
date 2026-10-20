{{--
    Conversa: lista de mensagens (role="log", novas mensagens são anunciadas).

    <x-ui::chat>
        <x-ui::chat.bubble from="user" time="14:02">Resuma os achados do PR #214</x-ui::chat.bubble>
        <x-ui::chat.bubble from="ai" name="DevAction IA" time="14:02">…</x-ui::chat.bubble>
    </x-ui::chat>
--}}
@props([
    'label' => 'Conversa',
])

<div data-chat role="log" aria-live="polite" aria-label="{{ $label }}" {{ $attributes->class(['flex flex-col gap-5']) }}>
    {{ $slot }}
</div>
