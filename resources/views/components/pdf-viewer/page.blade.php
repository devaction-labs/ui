{{--
    Página do visualizador: imagem (src) ou HTML no slot; campos de assinatura vão no slot também.

    <x-ui::pdf-viewer.page src="/contratos/2/pagina-1.png" />
--}}
@props([
    'src' => null,
    'alt' => null,
])

<div
    data-pdf-page
    x-show="isCurrent($el)"
    x-init="registerPage($el, @js($src))"
    style="width: calc(560px * var(--pdf-zoom, 1)); font-size: calc(14px * var(--pdf-zoom, 1))"
    x-bind:style="{ '--pdf-zoom': zoom / 100 }"
    {{ $attributes->class(['relative aspect-[1/1.414] shrink-0 overflow-hidden bg-white font-serif leading-[1.6] text-zinc-800 shadow-[0_8px_30px_-8px_rgb(24_24_27/0.3)]']) }}
>
    @if ($src)
        <img src="{{ $src }}" alt="{{ $alt ?? 'Página do documento' }}" class="absolute inset-0 size-full object-contain" />
    @endif
    <div @class(['relative size-full', 'p-[7%]' => ! $src])>{{ $slot }}</div>
</div>
