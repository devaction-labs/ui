{{--
    Tabela de planos com alternância mensal / anual.

    <x-ui::pricing yearly-badge="2 meses grátis" wire:model="yearly">
        <x-ui::pricing.plan name="Freelancer" description="1 pessoa, até 3 repositórios." :price="['monthly' => 'R$ 49', 'yearly' => 'R$ 39']" :features="['Review por IA em PRs', 'App no celular']" cta="Começar" href="/cadastro" />
        <x-ui::pricing.plan name="Agência" featured tag="Mais usado" … />
        <x-ui::pricing.plan name="Sob medida" price="Fale com a gente" cta="Falar com vendas" />
    </x-ui::pricing>

    yearly:        começa no anual (padrão true); wire:model / x-model recebem true/false
    monthly-label · yearly-label · yearly-badge: textos da alternância
    columns:       quantas colunas a partir do tablet (padrão 3)
--}}
@props([
    'yearly' => true,
    'monthlyLabel' => null,
    'yearlyLabel' => null,
    'yearlyBadge' => null,
    'heading' => null,
    'columns' => 3,
])

@php
    $monthlyLabel ??= __('Monthly');
    $yearlyLabel ??= __('Yearly');
@endphp

<section
    data-pricing
    x-data="{ yearly: @js((bool) $yearly) }"
    x-modelable="yearly"
    {{ $attributes->class(['flex flex-col gap-[18px]']) }}
>
    <div class="flex flex-wrap items-center gap-3">
        @if ($heading)
            <h2 class="text-[17px] font-semibold">{{ $heading }}</h2>
        @endif
        <div role="radiogroup" aria-label="{{ __('Billing period') }}" class="ml-auto inline-flex rounded-[10px] border border-line bg-wrap p-[3px] max-sm:mx-auto">
            @foreach ([false => $monthlyLabel, true => $yearlyLabel] as $isYearly => $periodLabel)
                <button
                    type="button"
                    role="radio"
                    x-on:click="yearly = @js((bool) $isYearly)"
                    x-bind:aria-checked="(yearly === @js((bool) $isYearly)).toString()"
                    x-bind:class="yearly === @js((bool) $isYearly) ? 'bg-card text-ink shadow-[0_1px_2px_rgb(0_0_0/0.08)]' : 'text-ink-3'"
                    class="inline-flex h-[30px] cursor-pointer items-center gap-1.5 rounded-[7px] px-3 text-[13px] transition"
                >
                    {{ $periodLabel }}
                    @if ($isYearly && $yearlyBadge)
                        <span class="inline-flex h-[18px] items-center rounded-[5px] bg-ok-bg px-1.5 text-[11px] text-ok-fg">{{ $yearlyBadge }}</span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <div @class(['grid gap-3.5', 'sm:grid-cols-2' => (int) $columns === 2, 'sm:grid-cols-2 lg:grid-cols-3' => (int) $columns === 3, 'sm:grid-cols-2 lg:grid-cols-4' => (int) $columns === 4])>
        {{ $slot }}
    </div>
</section>
