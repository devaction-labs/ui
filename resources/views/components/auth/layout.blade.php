{{--
    Layout das telas de autenticação.

    <x-ui::auth.layout brand="Acme CRM" href="/">
        <x-ui::auth.login action="/login" forgot-href="/esqueci-a-senha" register-href="/cadastro" />
    </x-ui::auth.layout>

    <x-ui::auth.layout variant="split">
        …formulário…
        <x-slot:aside>…depoimento, números, imagem…</x-slot:aside>
    </x-ui::auth.layout>

    variant: centered (padrão, card no meio do frame) · split (formulário à esquerda, painel à direita no desktop)
    slots:   aside (painel do split) · footer (links pequenos embaixo)
    fullscreen: ocupa a altura da tela (padrão true; false para prévias)
--}}
@props([
    'variant' => 'centered',
    'brand' => 'DevAction',
    'href' => '/',
    'fullscreen' => true,
])

@if ($variant === 'split')
    <div data-auth-layout data-variant="split" {{ $attributes->class(['grid bg-card lg:grid-cols-2', 'min-h-dvh' => $fullscreen]) }}>
        <div class="flex flex-col px-6 py-6 sm:px-10">
            <a href="{{ $href }}" class="flex w-fit items-center gap-2.5 rounded-[10px] outline-none focus-visible:ring-2 focus-visible:ring-ring">
                <x-ui::logo /><span class="text-[17px] font-semibold text-ink">{{ $brand }}</span>
            </a>
            <div class="flex flex-1 items-center justify-center py-10">
                <div class="w-full max-w-sm">{{ $slot }}</div>
            </div>
            @isset($footer)
                <div class="text-xs text-muted">{{ $footer }}</div>
            @endisset
        </div>
        <div class="relative m-3 hidden overflow-hidden rounded-[22px] bg-frame lg:block">
            <div class="absolute inset-0 bg-grid opacity-70 mask-fade-card" aria-hidden="true"></div>
            <div class="absolute inset-x-0 top-0 h-2/3 bg-ai-glow" aria-hidden="true"></div>
            <div class="relative flex h-full flex-col justify-end p-10">
                @isset($aside)
                    {{ $aside }}
                @else
                    <figure class="max-w-md">
                        <blockquote class="text-2xl leading-snug font-medium tracking-[-0.02em] text-ink">{{ __('“We stopped losing deals in the middle of the funnel: every lead has an owner, a next step and a deadline.”') }}</blockquote>
                        <figcaption class="mt-5 flex items-center gap-3 text-sm">
                            <x-ui::avatar name="Paula Ramos" size="sm" color="auto" circle />
                            <span class="flex flex-col"><span class="font-medium text-ink">Paula Ramos</span><span class="text-muted">{{ __('Sales director') }}</span></span>
                        </figcaption>
                    </figure>
                @endisset
            </div>
        </div>
    </div>
@else
    <div data-auth-layout data-variant="centered" {{ $attributes->class(['relative flex flex-col items-center justify-center overflow-hidden bg-frame px-4 py-10', 'min-h-dvh' => $fullscreen]) }}>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-80 bg-grid opacity-60 mask-fade-top" aria-hidden="true"></div>
        <a href="{{ $href }}" class="relative mb-7 flex items-center gap-2.5 rounded-[10px] outline-none focus-visible:ring-2 focus-visible:ring-ring">
            <x-ui::logo /><span class="text-[17px] font-semibold text-ink">{{ $brand }}</span>
        </a>
        <div class="relative w-full max-w-[420px] rounded-[22px] bg-wrap p-1.5">
            <div class="rounded-2xl border border-line bg-card p-7 shadow-[0_1px_2px_rgb(24_24_27/0.04)] max-sm:p-5">
                {{ $slot }}
            </div>
        </div>
        @isset($footer)
            <div class="relative mt-6 text-center text-xs text-muted">{{ $footer }}</div>
        @endisset
    </div>
@endif
