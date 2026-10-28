{{-- Login (Livewire): centered card bound to form.email, form.password and form.remember — see references/auth.md --}}
{{-- Livewire class: // app/Livewire/Auth/Login.php — public LoginForm $form (email, password, remember); method login() validates and signs in --}}
<x-ui::auth.layout brand="Acme" href="#" :fullscreen="false" class="w-full rounded-[18px]">
    <x-ui::auth.login wire:submit="login" model="form" forgot-href="#" register-href="#" magic-href="#" google="#" github="#" />
    <x-slot:footer>By signing in, you agree to the terms of use.</x-slot:footer>
</x-ui::auth.layout>
