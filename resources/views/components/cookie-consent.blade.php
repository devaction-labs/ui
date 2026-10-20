{{--
    Aviso de cookies (LGPD): aceitar todos, só os necessários ou escolher por categoria.
    A escolha fica no cookie ui_cookie_consent (JSON, 1 ano) e o aviso não volta; o servidor lê o mesmo cookie.

    <x-ui::cookie-consent policy="/privacidade" />
    <x-ui::cookie-consent :categories="['analytics' => ['Análise', 'Páginas visitadas, sem identificar você.']]" />

    categories: categorias opcionais [chave => [título, descrição]]; "Necessários" vem sempre ligado
    policy:     link da política de privacidade
    fixed:      false deixa o aviso no fluxo da página (padrão: fixo no rodapé)
    force:      mostra mesmo se a pessoa já escolheu (para uma página de preferências)
    Dispara cookie-consent-updated com { necessary, analytics, … } no detail.
--}}
@props([
    'categories' => [
        'analytics' => ['Análise', 'Páginas visitadas, sem identificar você.'],
        'marketing' => ['Marketing', 'Medir campanhas de anúncios.'],
    ],
    'policy' => null,
    'heading' => 'Usamos cookies.',
    'fixed' => true,
    'force' => false,
])

<section
    data-cookie-consent
    role="region"
    aria-label="Aviso de cookies"
    x-data="uiCookieConsent({ categories: @js(array_keys($categories)), force: @js((bool) $force) })"
    x-show="visible"
    x-cloak
    x-transition.opacity.duration.150ms
    {{ $attributes->class([
        'w-full max-w-[760px] rounded-[14px] border border-line bg-card px-[18px] py-4 text-ink shadow-[0_16px_40px_-16px_rgb(24_24_27/0.25)]',
        'fixed inset-x-4 bottom-4 z-50 mx-auto w-auto' => $fixed,
    ]) }}
>
    <div class="flex flex-wrap items-start gap-4">
        <p class="min-w-[240px] flex-1 text-sm leading-[1.55] text-ink-2">
            <strong class="font-semibold text-ink">{{ $heading }}</strong>
            @if ($slot->isEmpty())
                Os necessários mantêm você conectado. Os de análise nos ajudam a melhorar o site e só ligam se você aceitar.
            @else
                {{ $slot }}
            @endif
            @if ($policy)
                <a href="{{ $policy }}" class="underline underline-offset-[3px] hover:text-ink">Política de privacidade</a>
            @endif
        </p>
        <div class="flex shrink-0 flex-wrap gap-2">
            <x-ui::button size="sm" x-on:click="preferences = ! preferences" x-bind:aria-expanded="preferences.toString()">Preferências</x-ui::button>
            <x-ui::button size="sm" x-show="! preferences" x-on:click="save(false)">Só necessários</x-ui::button>
            <x-ui::button size="sm" variant="primary" x-show="! preferences" x-on:click="save(true)">Aceitar todos</x-ui::button>
            <x-ui::button size="sm" variant="primary" x-show="preferences" x-cloak x-on:click="save()">Salvar escolha</x-ui::button>
        </div>
    </div>

    <div x-show="preferences" x-cloak class="mt-3.5 border-t border-line-soft pt-1.5">
        <div class="flex items-center gap-3 border-b border-line-soft py-2.5 text-sm">
            <div class="flex-1"><div class="font-medium">Necessários</div><div class="text-xs text-muted">Sessão, segurança e preferências de tema. Sempre ligados.</div></div>
            <x-ui::switch checked disabled aria-label="Necessários (sempre ligados)" size="sm" />
        </div>
        @foreach ($categories as $key => [$title, $description])
            <div class="flex items-center gap-3 border-b border-line-soft py-2.5 text-sm last:border-b-0">
                <div class="flex-1"><div class="font-medium">{{ $title }}</div><div class="text-xs text-muted">{{ $description }}</div></div>
                <x-ui::switch x-model="choices.{{ $key }}" :aria-label="$title" size="sm" />
            </div>
        @endforeach
    </div>
</section>
