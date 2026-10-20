{{--
    Símbolo D da DevAction. A frente usa a cor do card e a parte de trás a cor da IA,
    então acompanha o tema sozinho.

    <x-ui::logo />
    <x-ui::logo size="md" />
    <x-ui::logo>DevAction UI</x-ui::logo>

    size: xs (30) · sm (36, padrão) · md (64) · lg (176)
--}}
@props([
    'size' => 'sm',
])

@php
    $tileClasses = match ($size) {
        'xs' => 'size-7.5 rounded-lg',
        'md' => 'size-16 rounded-[18px]',
        'lg' => 'size-44 rounded-[40px]',
        default => 'size-9 rounded-[10px]',
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-2.5']) }}>
    <span class="{{ $tileClasses }} flex shrink-0 items-center justify-center bg-navy">
        <svg viewBox="50 50 1154 1154" class="size-full" @if ($slot->isEmpty()) role="img" aria-label="DevAction" @else aria-hidden="true" @endif>
            <path class="fill-card" d="M395 303 H728 L912 492 Q968 552 968 628 Q968 706 905 770 L828 832 Q834 800 812 776 L392 330 Q380 314 395 303 Z" />
            <path class="fill-ai" d="M628 662 Q641 650 654 662 L797 808 Q812 826 797 843 L722 935 Q712 945 698 945 H388 Q366 945 380 925 Z" />
        </svg>
    </span>
    @if ($slot->isNotEmpty())
        <span class="text-[17px] font-semibold tracking-[-0.01em]">{{ $slot }}</span>
    @endif
</span>
