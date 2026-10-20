{{--
    Botões de entrar com outra conta, seguidos do divisor "ou".

    <x-ui::auth.social google="/auth/google" github="/auth/github" />

    google · github: endereço do redirecionamento OAuth de cada provedor (sem ele, o botão não aparece)
    divider:         mostra o "ou com e-mail" embaixo (padrão true)
--}}
@props([
    'google' => null,
    'github' => null,
    'divider' => true,
])

@if ($google || $github)
    <div data-auth-social {{ $attributes->class(['flex flex-col gap-4']) }}>
        <div @class(['grid gap-2', 'grid-cols-2' => $google && $github])>
            @if ($google)
                <x-ui::button :href="$google" icon="google" class="w-full">Google</x-ui::button>
            @endif
            @if ($github)
                <x-ui::button :href="$github" icon="github" class="w-full">GitHub</x-ui::button>
            @endif
        </div>
        @if ($divider)
            <div class="flex items-center gap-3 text-xs text-muted" role="separator">
                <span class="h-px flex-1 bg-line"></span>ou com e-mail<span class="h-px flex-1 bg-line"></span>
            </div>
        @endif
    </div>
@endif
