{{-- Login: centered card with email, password and remember fields, POST with CSRF — see references/auth.md --}}
<x-ui::auth.layout brand="Acme" href="#" :fullscreen="false" class="w-full rounded-[18px]">
    <x-ui::auth.login action="#" forgot-href="#" register-href="#" magic-href="#" google="#" github="#" />
    <x-slot:footer>By signing in, you agree to the terms of use.</x-slot:footer>
</x-ui::auth.layout>
