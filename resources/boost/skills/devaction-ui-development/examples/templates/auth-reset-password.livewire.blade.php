{{-- Reset password (Livewire): request step bound to form.email, and the new-password step — see references/auth.md --}}
{{-- Livewire class: // app/Livewire/Auth/ResetPassword.php — public ResetPasswordForm $form (email, token, password); method sendReset() sends the link, reset() saves the new password --}}
<div class="grid w-full gap-4 lg:grid-cols-2">
    <x-ui::auth.layout brand="Acme" href="#" :fullscreen="false" class="rounded-[18px]">
        <x-ui::auth.reset-password wire:submit="sendReset" model="form" login-href="#" />
    </x-ui::auth.layout>

    <x-ui::auth.layout brand="Acme" href="#" :fullscreen="false" class="rounded-[18px]">
        <x-ui::auth.reset-password wire:submit="reset" model="form" mode="reset" token="abc123" email="ana@acme.com" login-href="#" />
    </x-ui::auth.layout>
</div>
