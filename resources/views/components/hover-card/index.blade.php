{{--
    Hover card: cartão de pessoa, PR ou empresa que abre ao passar o mouse (400 ms) ou no foco do teclado.
    Nunca coloque ação obrigatória nele: tudo também precisa estar no link.

    <x-ui::hover-card>
        <a href="/pessoas/alex" class="font-medium underline decoration-dotted underline-offset-4">Alex</a>
        <x-slot:card>
            <x-ui::hover-card.person name="Alex" description="Dev · DevAction Labs" online :stats="['PRs abertos' => 4, 'na semana' => '32 h', 'projetos' => 2]" />
        </x-slot:card>
    </x-ui::hover-card>

    position: bottom (padrão) · top · right · left · align: start (padrão) · center · end
    delay:    milissegundos até abrir (padrão 400)
--}}
@props([
    'position' => 'bottom',
    'align' => 'start',
    'delay' => 400,
])

<span
    data-hover-card
    x-data="uiHoverCard({ delay: {{ (int) $delay }} })"
    x-id="['ui-hover-card']"
    x-on:mouseenter="schedule()"
    x-on:mouseleave="leave()"
    x-on:focusin="show()"
    x-on:focusout="leave()"
    x-on:keydown.escape="hide()"
    {{ $attributes->class(['relative inline']) }}
>
    {{ $slot }}

    <span
        x-bind:id="$id('ui-hover-card')"
        x-show="open"
        x-ui-float="{ placement: @js($position), align: @js($align), gap: 8 }"
        x-cloak
        x-transition.opacity.duration.120ms
        x-on:mouseenter="show()"
        x-on:mouseleave="leave()"
        @class(['absolute top-full left-0 z-50 mt-2 block w-[300px] rounded-[14px] border border-line bg-card p-4 text-left text-sm font-normal text-ink no-underline shadow-[0_16px_40px_-12px_rgb(24_24_27/0.28)]', isset($card) ? $card->attributes->get('class') : null])
    >
        {{ $card ?? '' }}
    </span>
</span>
