{{--
    Formulário de cadastro pronto: nome, e-mail, senha, confirmação e termos.

    <x-ui::auth.register action="{{ route('register') }}" login-href="/login" terms-href="/termos" />
    <x-ui::auth.register wire:submit="register" model="form" google="/auth/google" />

    Campos: name, email, password, password_confirmation e terms.
--}}
@props([
    'action' => null,
    'model' => null,
    'heading' => null,
    'description' => null,
    'loginHref' => null,
    'termsHref' => null,
    'google' => null,
    'github' => null,
])

@php
    $heading ??= __('Create account');
    $description ??= __('Start for free. It takes less than a minute.');
@endphp

<div data-auth-form="register">
    <x-ui::auth.heading :heading="$heading" :description="$description" />
    <x-ui::auth.social :google="$google" :github="$github" class="mb-4" />

    <form @if ($action) method="POST" action="{{ $action }}" @endif {{ $attributes->class(['flex flex-col gap-4']) }}>
        @if ($action)
            @csrf
        @endif
        <x-ui::input name="name" :wire:model="$model ? $model.'.name' : null" :label="__('Name')" icon="user" autocomplete="name" required autofocus />
        <x-ui::input type="email" name="email" :wire:model="$model ? $model.'.email' : null" :label="__('Work email')" icon="mail-01" autocomplete="email" :placeholder="__('you@company.com')" required />
        <x-ui::input type="password" name="password" :wire:model="$model ? $model.'.password' : null" :label="__('Password')" icon="lock-password" autocomplete="new-password" :description="__('At least 8 characters.')" viewable required />
        <x-ui::input type="password" name="password_confirmation" :wire:model="$model ? $model.'.password_confirmation' : null" :label="__('Confirm password')" icon="lock-password" autocomplete="new-password" viewable required />
        @if ($termsHref)
            <x-ui::checkbox name="terms" :wire:model="$model ? $model.'.terms' : null" value="1" required>
                <x-slot:label>{!! __('I accept the :terms', ['terms' => '<a href="'.e($termsHref).'" class="font-medium text-ink underline underline-offset-4">'.e(__('terms of use')).'</a>']) !!}</x-slot:label>
            </x-ui::checkbox>
        @endif
        <x-ui::button type="submit" variant="primary" size="lg" class="mt-1 w-full">{{ __('Create account') }}</x-ui::button>
    </form>

    @if ($loginHref)
        <p class="mt-6 text-center text-sm text-ink-3">{{ __('Already have an account?') }} <a href="{{ $loginHref }}" class="font-medium text-ink underline-offset-4 hover:underline">{{ __('Sign in') }}</a></p>
    @endif
</div>
