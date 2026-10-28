{{-- Centered layout: a 720px column for settings, onboarding or checkout, with logo header and a form — see references/layouts.md --}}
<x-ui::layout.centered width="720px">
    <x-slot:header>
        <x-ui::logo size="xs">Acme</x-ui::logo>
    </x-slot:header>

    <x-ui::page-header heading="Settings" description="Update your company profile." />

    <div class="mt-6 flex flex-col gap-5 rounded-xl border border-line bg-card p-6">
        <x-ui::input label="Company name" value="Acme Labs" />
        <x-ui::input label="Billing email" type="email" value="billing@acme.com" icon="mail-01" />

        <div class="flex justify-end gap-2">
            <x-ui::button>Cancel</x-ui::button>
            <x-ui::button variant="primary">Save changes</x-ui::button>
        </div>
    </div>
</x-ui::layout.centered>
