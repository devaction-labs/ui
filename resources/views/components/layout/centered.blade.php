{{--
    Tema centralizado: coluna estreita no meio, para leitura, configurações, onboarding e checkout.

    <x-ui::layout.centered>
        <x-slot:header><x-ui::logo size="xs">Acme</x-ui::logo></x-slot:header>
        <x-ui::page-header heading="Configurações" />
        …
    </x-ui::layout.centered>

    width: largura máxima da coluna (padrão 720px)
--}}
@props([
    'width' => '720px',
])

<div data-layout="centered" {{ $attributes->class(['flex min-h-screen flex-col bg-frame text-ink']) }}>
    @isset($header)
        <header class="border-b border-line bg-panel">
            <div class="mx-auto flex h-14 items-center gap-3 px-4" style="max-width: {{ $width }}">{{ $header }}</div>
        </header>
    @endisset
    <main class="mx-auto w-full flex-1 px-4 py-8 md:py-12" style="max-width: {{ $width }}">{{ $slot }}</main>
    @isset($footer)
        <footer class="mx-auto w-full px-4 pb-8 text-[13px] text-muted" style="max-width: {{ $width }}">{{ $footer }}</footer>
    @endisset
</div>
