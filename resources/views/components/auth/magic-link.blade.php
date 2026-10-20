{{--
    Entrar sem senha: pede o e-mail e confirma o envio do link mágico.

    <x-ui::auth.magic-link action="/link-magico" login-href="/login" />
    <x-ui::auth.magic-link sent email="ana@acme.com" resend-action="/link-magico" />

    sent:  mostra a confirmação ("Confira seu e-mail") em vez do formulário
    model: prefixo do wire:model (form.email)
--}}
@props([
    'action' => null,
    'model' => null,
    'sent' => false,
    'email' => null,
    'resendAction' => null,
    'loginHref' => null,
])

<div data-auth-form="magic-link">
    @if ($sent)
        <div class="flex flex-col items-center text-center">
            <span class="mb-5 flex size-14 items-center justify-center rounded-2xl bg-warn-bg text-warn-fg">
                <x-ui::icon name="mail-open-01" class="size-7" />
            </span>
            <x-ui::auth.heading heading="Confira seu e-mail" class="items-center">
                <p class="text-sm text-ink-3">Enviamos um link de acesso para @if ($email)<strong class="font-medium text-ink">{{ $email }}</strong>@else o seu e-mail @endif. Ele vale por 15 minutos.</p>
            </x-ui::auth.heading>
            <div class="flex w-full flex-col gap-2">
                @if ($resendAction)
                    <form method="POST" action="{{ $resendAction }}" class="w-full">
                        @csrf
                        @if ($email)
                            <input type="hidden" name="email" value="{{ $email }}" />
                        @endif
                        <x-ui::button type="submit" class="w-full">Reenviar link</x-ui::button>
                    </form>
                @endif
                {{ $slot }}
                @if ($loginHref)
                    <x-ui::button :href="$loginHref" variant="ghost" class="w-full">Entrar com senha</x-ui::button>
                @endif
            </div>
        </div>
    @else
        <x-ui::auth.heading heading="Entrar com link mágico" description="Mandamos um link para o seu e-mail. Sem senha para lembrar." />
        <form @if ($action) method="POST" action="{{ $action }}" @endif {{ $attributes->class(['flex flex-col gap-4']) }}>
            @if ($action)
                @csrf
            @endif
            <x-ui::input type="email" name="email" :wire:model="$model ? $model.'.email' : null" label="E-mail" icon="mail-01" autocomplete="email" placeholder="voce@empresa.com" required autofocus />
            <x-ui::button type="submit" variant="primary" size="lg" icon="mail-01" class="w-full">Enviar link</x-ui::button>
        </form>
        @if ($loginHref)
            <p class="mt-6 text-center text-sm text-ink-3"><a href="{{ $loginHref }}" class="font-medium text-ink underline-offset-4 hover:underline">Entrar com senha</a></p>
        @endif
    @endif
</div>
