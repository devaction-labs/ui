{{--
    Conteúdo do item.

    <x-ui::accordion.content>Não. Ela lê o diff do PR, faz o review e descarta.</x-ui::accordion.content>

    Fechado, fica com hidden="until-found": o Ctrl+F do navegador encontra o texto e abre o item
    sozinho (evento beforematch). Com transition no acordeão, a altura anima via x-collapse.
--}}
@aware([
    'variant' => 'default',
    'expanded' => false,
    'transition' => false,
])

@php
    $paddingClasses = match ($variant) {
        'reverse' => 'pr-2 pb-3 pl-7',
        'group' => 'pr-3.5 pb-3.5 pl-[38px]',
        'cards' => 'border-t border-line-soft pt-1 pr-3.5 pb-3.5 pl-[42px]',
        default => 'pr-8 pb-[18px] pl-0.5',
    };
@endphp

<div
    role="region"
    x-bind:id="$id('accordion-panel', itemId)"
    x-bind:aria-labelledby="$id('accordion-trigger', itemId)"
    @unless ($expanded) hidden="until-found" @endunless
    x-effect="open ? $el.removeAttribute('hidden') : $el.setAttribute('hidden', 'until-found')"
    x-on:beforematch="open || toggle()"
    @if ($transition) x-show="open" x-collapse @endif
>
    <div {{ $attributes->class(['text-sm leading-[1.6] text-ink-3', $paddingClasses]) }}>
        {{ $slot }}
    </div>
</div>
