{{-- Register: account form with terms checkbox in the split layout, POST with CSRF — see references/auth.md --}}
<x-ui::auth.layout variant="split" brand="Acme" href="#" :fullscreen="false" class="w-full overflow-hidden rounded-[18px] border border-line">
    <x-ui::auth.register action="#" login-href="#" terms-href="#" google="#" />
</x-ui::auth.layout>
