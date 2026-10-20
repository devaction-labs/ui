{{--
    Card com moldura de 6px (wrap › card), como no web claro/escuro.

    <x-ui::card>Conteúdo</x-ui::card>
    <x-ui::card :wrap="false" class="p-4">Card simples</x-ui::card>
--}}
@props([
    'wrap' => true,
])

@if ($wrap)
    <div class="rounded-wrap bg-wrap p-1.5">
        <div {{ $attributes->class(['rounded-card border border-line bg-card p-6 text-ink']) }}>
            {{ $slot }}
        </div>
    </div>
@else
    <div {{ $attributes->class(['rounded-card border border-line bg-card p-6 text-ink']) }}>
        {{ $slot }}
    </div>
@endif
