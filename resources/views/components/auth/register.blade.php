{{--
    Formulário de cadastro pronto: nome, e-mail, senha, confirmação e termos.

    <x-ui::auth.register action="{{ route('register') }}" login-href="/login" terms-href="/termos" />
    <x-ui::auth.register wire:submit="register" model="form" google="/auth/google" />

    Campos: name, email, password, password_confirmation e terms.
--}}
@props([
    'action' => null,
    'model' => null,
    'heading' => 'Criar conta',
    'description' => 'Comece grátis. Leva menos de um minuto.',
    'loginHref' => null,
    'termsHref' => null,
    'google' => null,
    'github' => null,
])

<div data-auth-form="register">
    <x-ui::auth.heading :heading="$heading" :description="$description" />
    <x-ui::auth.social :google="$google" :github="$github" class="mb-4" />

    <form @if ($action) method="POST" action="{{ $action }}" @endif {{ $attributes->class(['flex flex-col gap-4']) }}>
        @if ($action)
            @csrf
        @endif
        <x-ui::input name="name" :wire:model="$model ? $model.'.name' : null" label="Nome" icon="user" autocomplete="name" required autofocus />
        <x-ui::input type="email" name="email" :wire:model="$model ? $model.'.email' : null" label="E-mail de trabalho" icon="mail-01" autocomplete="email" placeholder="voce@empresa.com" required />
        <x-ui::input type="password" name="password" :wire:model="$model ? $model.'.password' : null" label="Senha" icon="lock-password" autocomplete="new-password" description="Pelo menos 8 caracteres." viewable required />
        <x-ui::input type="password" name="password_confirmation" :wire:model="$model ? $model.'.password_confirmation' : null" label="Confirmar senha" icon="lock-password" autocomplete="new-password" viewable required />
        @if ($termsHref)
            <x-ui::checkbox name="terms" :wire:model="$model ? $model.'.terms' : null" value="1" required>
                <x-slot:label>Li e aceito os <a href="{{ $termsHref }}" class="font-medium text-ink underline underline-offset-4">termos de uso</a></x-slot:label>
            </x-ui::checkbox>
        @endif
        <x-ui::button type="submit" variant="primary" size="lg" class="mt-1 w-full">Criar conta</x-ui::button>
    </form>

    @if ($loginHref)
        <p class="mt-6 text-center text-sm text-ink-3">Já tem conta? <a href="{{ $loginHref }}" class="font-medium text-ink underline-offset-4 hover:underline">Entrar</a></p>
    @endif
</div>
