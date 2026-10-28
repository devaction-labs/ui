{{-- Register (Livewire): split layout bound to form.email, form.password and form.terms — see references/auth.md --}}
{{-- Livewire class: // app/Livewire/Auth/Register.php — public RegisterForm $form (email, password, terms); method register() creates the account --}}
<x-ui::auth.layout variant="split" brand="Acme" href="#" :fullscreen="false" class="w-full overflow-hidden rounded-[18px] border border-line">
    <x-ui::auth.register wire:submit="register" model="form" login-href="#" terms-href="#" google="#" />
</x-ui::auth.layout>
