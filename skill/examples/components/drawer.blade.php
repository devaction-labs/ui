{{-- Drawer: side panel with a form and footer actions, plus a bottom sheet variation — see references/drawer.md --}}
@php
    $profile = ['name' => 'Alex Nogueira', 'about' => 'Elixir/Phoenix and Laravel. I like reviewing data modelling and concurrency.'];
@endphp

<div class="flex w-full max-w-md flex-col gap-4">
    {{-- Main usage: floating drawer (right side) with heading, body and footer --}}
    <x-ui::modal.trigger name="drawer-profile">
        <x-ui::button>Edit profile</x-ui::button>
    </x-ui::modal.trigger>

    <x-ui::drawer name="drawer-profile" heading="Edit profile" description="Update your personal details.">
        <label class="flex flex-col gap-1.5 font-medium">
            Name
            <input type="text" value="{{ $profile['name'] }}" class="h-11 rounded-xl border border-line-strong bg-card px-3.5 font-normal text-ink outline-none focus:border-ink focus:ring-4 focus:ring-ink/10" />
        </label>
        <label class="flex flex-col gap-1.5 font-medium">
            About you
            <textarea rows="3" class="rounded-xl border border-line-strong bg-card px-3.5 py-3 font-normal text-ink outline-none focus:border-ink focus:ring-4 focus:ring-ink/10">{{ $profile['about'] }}</textarea>
        </label>
        <x-slot:footer>
            <x-ui::modal.close>
                <x-ui::button variant="filled">Cancel</x-ui::button>
            </x-ui::modal.close>
            <x-ui::button variant="primary">Save changes</x-ui::button>
        </x-slot:footer>
    </x-ui::drawer>

    {{-- Variation: bottom sheet, great on phones --}}
    <x-ui::modal.trigger name="drawer-sheet">
        <x-ui::button size="sm">Bottom sheet</x-ui::button>
    </x-ui::modal.trigger>

    <x-ui::drawer name="drawer-sheet" side="bottom" heading="New task" description="Short form for phones.">
        <p class="text-muted">A short form goes here.</p>
    </x-ui::drawer>
</div>
