{{-- Magic link: email form and the sent confirmation with resend, side by side — see references/auth.md --}}
<div class="grid w-full gap-4 lg:grid-cols-2">
    <x-ui::auth.layout brand="Acme" href="#" :fullscreen="false" class="rounded-[18px]">
        <x-ui::auth.magic-link action="#" login-href="#" />
    </x-ui::auth.layout>

    <x-ui::auth.layout brand="Acme" href="#" :fullscreen="false" class="rounded-[18px]">
        <x-ui::auth.magic-link sent email="ana@acme.com" resend-action="#" login-href="#" />
    </x-ui::auth.layout>
</div>
