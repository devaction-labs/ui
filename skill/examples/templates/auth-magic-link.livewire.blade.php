{{-- Magic link (Livewire): email form bound to form.email, with the sent confirmation — see references/auth.md --}}
{{-- Livewire class: // app/Livewire/Auth/MagicLink.php — public MagicLinkForm $form (email); method sendLink() emails the link and sets sent --}}
<div class="grid w-full gap-4 lg:grid-cols-2">
    <x-ui::auth.layout brand="Acme" href="#" :fullscreen="false" class="rounded-[18px]">
        <x-ui::auth.magic-link wire:submit="sendLink" model="form" login-href="#" />
    </x-ui::auth.layout>

    <x-ui::auth.layout brand="Acme" href="#" :fullscreen="false" class="rounded-[18px]">
        <x-ui::auth.magic-link sent email="ana@acme.com" resend-action="#" login-href="#" />
    </x-ui::auth.layout>
</div>
