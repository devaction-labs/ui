{{-- Reset password: request step and new-password step, side by side — see references/auth.md --}}
<div class="grid w-full gap-4 lg:grid-cols-2">
    <x-ui::auth.layout brand="Acme" href="#" :fullscreen="false" class="rounded-[18px]">
        <x-ui::auth.reset-password action="#" login-href="#" />
    </x-ui::auth.layout>

    <x-ui::auth.layout brand="Acme" href="#" :fullscreen="false" class="rounded-[18px]">
        <x-ui::auth.reset-password mode="reset" action="#" token="abc123" email="ana@acme.com" login-href="#" />
    </x-ui::auth.layout>
</div>
