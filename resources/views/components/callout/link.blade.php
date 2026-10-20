{{--
    Link dentro do texto do callout.

    <x-ui::callout.link href="/integracoes">Ver integrações</x-ui::callout.link>
    <x-ui::callout.link href="https://github.com" external>Abrir no GitHub</x-ui::callout.link>
--}}
@props([
    'href',
    'external' => false,
])

<a
    href="{{ $href }}"
    @if ($external) target="_blank" rel="noopener noreferrer" @endif
    {{ $attributes->class(['font-medium underline underline-offset-4 hover:opacity-80']) }}
>{{ $slot }}</a>
