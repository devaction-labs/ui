{{--
    Alerta: um callout com ícone padrão por variante e papel ARIA de status.
    Cor nunca sozinha: todo alerta tem ícone e título.

    <x-ui::alert variant="success" heading="GitHub conectado" text="6 repositórios sincronizados." />
    <x-ui::alert variant="ai" heading="A IA está revisando" text="Leva cerca de 1 minuto por PR." />
    <x-ui::alert variant="danger" heading="Webhook falhou">
        Reenviando em 30 s.
        <x-slot:actions><x-ui::button size="sm">Tentar agora</x-ui::button></x-slot:actions>
    </x-ui::alert>

    variant: secondary (padrão) · success · warning · danger · ai · info
    danger usa role="alert" (anunciado na hora); as demais, role="status".
--}}
@props([
    'variant' => 'secondary',
    'icon' => null,
    'heading' => null,
    'text' => null,
    'inline' => false,
    'actions' => null,
    'controls' => null,
])

@php
    $icon ??= match ($variant) {
        'success' => 'checkmark-circle-02',
        'warning' => 'alert-02',
        'danger' => 'cancel-circle',
        'ai' => 'sparkles',
        'info' => 'idea-01',
        default => 'information-circle',
    };
@endphp

<x-ui::callout
    :variant="$variant"
    :icon="$icon"
    :heading="$heading"
    :text="$text"
    :inline="$inline"
    :actions="$actions"
    :controls="$controls"
    :attributes="$attributes->merge(['role' => $variant === 'danger' ? 'alert' : 'status'])"
>{{ $slot }}</x-ui::callout>
