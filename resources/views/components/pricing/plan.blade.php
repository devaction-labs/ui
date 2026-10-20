{{--
    Plano da tabela de preços.

    <x-ui::pricing.plan name="Agência" description="Para times de até 10 pessoas." featured tag="Mais usado"
        :price="['monthly' => 'R$ 199', 'yearly' => 'R$ 159']" per="/mês"
        :features="['Tudo do Freelancer', 'Contratos e banco de horas']" cta="Criar conta da agência" wire:click="choose('agencia')" />

    price:    texto fixo ("Fale com a gente") ou ['monthly' => …, 'yearly' => …]
    per:      período ao lado do preço (padrão /mês quando o preço muda com a cobrança)
    note:     linha abaixo do preço; padrão "cobrado anualmente" / "cobrado todo mês"
    cta:      texto do botão; href vira link; wire:click e x-on:click vão para o botão
    featured: borda forte e botão principal · tag: etiqueta em cima do cartão
--}}
@props([
    'name',
    'description' => null,
    'price' => null,
    'per' => null,
    'note' => null,
    'features' => [],
    'cta' => null,
    'href' => null,
    'featured' => false,
    'tag' => null,
])

@php
    $isRecurring = is_array($price);
    $per ??= $isRecurring ? '/mês' : '';
    $actionAttributes = $attributes->filter(fn ($value, string $key): bool => str_starts_with($key, 'wire:') || str_starts_with($key, 'x-on:'));
    $cardAttributes = $attributes->except(array_keys($actionAttributes->getAttributes()));
@endphp

<article
    data-pricing-plan
    {{ $cardAttributes->class([
        'relative flex flex-col rounded-[14px] bg-card p-5',
        'border-2 border-ink' => $featured,
        'border border-line' => ! $featured,
    ]) }}
>
    @if ($tag)
        <span class="absolute -top-[11px] left-5 flex h-[22px] items-center rounded-md bg-ai px-2 text-[11px] font-semibold text-white uppercase">{{ $tag }}</span>
    @endif

    <h3 class="text-base font-semibold">{{ $name }}</h3>
    @if ($description)
        <p class="mt-1 min-h-9 text-[13px] text-muted">{{ $description }}</p>
    @endif

    <p class="mt-3 flex items-baseline gap-1">
        @if ($isRecurring)
            <span class="text-[30px] leading-9 font-semibold tracking-[-0.02em]" x-text="yearly ? @js($price['yearly'] ?? '') : @js($price['monthly'] ?? '')">{{ $price['yearly'] ?? $price['monthly'] ?? '' }}</span>
        @else
            <span class="text-[22px] leading-9 font-semibold tracking-[-0.02em]">{{ $price }}</span>
        @endif
        @if ($per)
            <span class="text-[13px] text-muted">{{ $per }}</span>
        @endif
    </p>
    <p class="mt-0.5 min-h-4 text-xs text-muted">
        @if ($note)
            {{ $note }}
        @elseif ($isRecurring)
            <span x-text="yearly ? 'cobrado anualmente' : 'cobrado todo mês'">cobrado anualmente</span>
        @endif
    </p>

    @if ($features !== [])
        <ul class="mt-3.5 flex flex-col gap-2 text-[13px] text-ink-2">
            @foreach ($features as $feature)
                <li class="flex items-start gap-2"><x-ui::icon name="tick-02" class="mt-px size-[15px] text-ok-fg" />{{ $feature }}</li>
            @endforeach
        </ul>
    @endif

    {{ $slot }}

    @if ($cta)
        <div class="mt-auto pt-[18px]">
            <x-ui::button :href="$href" :variant="$featured ? 'primary' : 'outline'" class="w-full" :attributes="$actionAttributes">{{ $cta }}</x-ui::button>
        </div>
    @endif
</article>
