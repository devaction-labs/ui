{{--
    Formulário de login pronto: e-mail, senha, lembrar e links.

    <x-ui::auth.login action="{{ route('login') }}" forgot-href="/esqueci-a-senha" register-href="/cadastro" />
    <x-ui::auth.login wire:submit="login" model="form" magic-href="/link-magico" google="/auth/google" />

    action: envia por POST com @csrf · model: prefixo do wire:model (form.email, form.password, form.remember)
    Os campos se chamam email, password e remember; os erros de validação aparecem neles.
--}}
@props([
    'action' => null,
    'model' => null,
    'heading' => null,
    'description' => null,
    'forgotHref' => null,
    'registerHref' => null,
    'magicHref' => null,
    'google' => null,
    'github' => null,
])

@php
    $heading ??= __('Sign in');
    $description ??= __('Welcome back. Sign in with your email.');
@endphp

<div data-auth-form="login">
    <x-ui::auth.heading :heading="$heading" :description="$description" />
    <x-ui::auth.social :google="$google" :github="$github" class="mb-4" />

    <form @if ($action) method="POST" action="{{ $action }}" @endif {{ $attributes->class(['flex flex-col gap-4']) }}>
        @if ($action)
            @csrf
        @endif
        <x-ui::input type="email" name="email" :wire:model="$model ? $model.'.email' : null" :label="__('Email')" icon="mail-01" autocomplete="username" :placeholder="__('you@company.com')" required autofocus />
        <x-ui::input type="password" name="password" :wire:model="$model ? $model.'.password' : null" :label="__('Password')" icon="lock-password" autocomplete="current-password" viewable required />
        <div class="flex items-center justify-between gap-3">
            <x-ui::checkbox name="remember" :wire:model="$model ? $model.'.remember' : null" :label="__('Remember me')" value="1" />
            @if ($forgotHref)
                <a href="{{ $forgotHref }}" class="text-[13px] font-medium text-ink underline-offset-4 hover:underline">{{ __('Forgot password?') }}</a>
            @endif
        </div>
        <x-ui::button type="submit" variant="primary" size="lg" class="mt-1 w-full">{{ __('Sign in') }}</x-ui::button>
        @if ($magicHref)
            <x-ui::button :href="$magicHref" variant="ghost" icon="mail-01" class="w-full">{{ __('Email me a magic link') }}</x-ui::button>
        @endif
    </form>

    @if ($registerHref)
        <p class="mt-6 text-center text-sm text-ink-3">{{ __('Don\'t have an account?') }} <a href="{{ $registerHref }}" class="font-medium text-ink underline-offset-4 hover:underline">{{ __('Create account') }}</a></p>
    @endif
</div>
