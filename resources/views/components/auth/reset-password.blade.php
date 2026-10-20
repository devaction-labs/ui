{{--
    Redefinir senha em dois passos: pedir o link (request) e criar a nova senha (reset).

    <x-ui::auth.reset-password action="{{ route('password.email') }}" login-href="/login" />
    <x-ui::auth.reset-password mode="reset" :token="$token" :email="$email" action="{{ route('password.update') }}" />
    <x-ui::auth.reset-password sent email="ana@acme.com" />

    mode:  request (padrão) · reset
    sent:  no request, mostra a confirmação de envio
    Campos: email; no reset também token, password e password_confirmation.
--}}
@props([
    'mode' => 'request',
    'action' => null,
    'model' => null,
    'token' => null,
    'email' => null,
    'sent' => false,
    'loginHref' => null,
])

<div data-auth-form="reset-password" data-mode="{{ $mode }}">
    @if ($mode === 'reset')
        <x-ui::auth.heading heading="Criar nova senha" description="Escolha uma senha que você não usa em outro lugar." />
        <form @if ($action) method="POST" action="{{ $action }}" @endif {{ $attributes->class(['flex flex-col gap-4']) }}>
            @if ($action)
                @csrf
            @endif
            @if ($token)
                <input type="hidden" name="token" value="{{ $token }}" />
            @endif
            <x-ui::input type="email" name="email" :wire:model="$model ? $model.'.email' : null" label="E-mail" icon="mail-01" autocomplete="username" :value="$email" required />
            <x-ui::input type="password" name="password" :wire:model="$model ? $model.'.password' : null" label="Nova senha" icon="lock-password" autocomplete="new-password" description="Pelo menos 8 caracteres." viewable required autofocus />
            <x-ui::input type="password" name="password_confirmation" :wire:model="$model ? $model.'.password_confirmation' : null" label="Confirmar nova senha" icon="lock-password" autocomplete="new-password" viewable required />
            <x-ui::button type="submit" variant="primary" size="lg" class="mt-1 w-full">Salvar nova senha</x-ui::button>
        </form>
    @elseif ($sent)
        <div class="flex flex-col items-center text-center">
            <span class="mb-5 flex size-14 items-center justify-center rounded-2xl bg-ok-bg text-ok-fg">
                <x-ui::icon name="mail-open-01" class="size-7" />
            </span>
            <x-ui::auth.heading heading="Link enviado" class="items-center">
                <p class="text-sm text-ink-3">Se existir uma conta com @if ($email)<strong class="font-medium text-ink">{{ $email }}</strong>@else esse e-mail @endif, você vai receber o link para criar uma nova senha.</p>
            </x-ui::auth.heading>
            @if ($loginHref)
                <x-ui::button :href="$loginHref" class="w-full">Voltar para o login</x-ui::button>
            @endif
        </div>
    @else
        <x-ui::auth.heading heading="Esqueceu a senha?" description="Digite o seu e-mail e mandamos um link para criar uma nova." />
        <form @if ($action) method="POST" action="{{ $action }}" @endif {{ $attributes->class(['flex flex-col gap-4']) }}>
            @if ($action)
                @csrf
            @endif
            <x-ui::input type="email" name="email" :wire:model="$model ? $model.'.email' : null" label="E-mail" icon="mail-01" autocomplete="email" placeholder="voce@empresa.com" required autofocus />
            <x-ui::button type="submit" variant="primary" size="lg" class="w-full">Enviar link</x-ui::button>
        </form>
        @if ($loginHref)
            <p class="mt-6 text-center text-sm text-ink-3">Lembrou? <a href="{{ $loginHref }}" class="font-medium text-ink underline-offset-4 hover:underline">Voltar para o login</a></p>
        @endif
    @endif
</div>
