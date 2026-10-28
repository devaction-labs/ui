{{-- Input group: inputs with icons, prefixes, copy and password toggles, sizes and a select attached — see references/input-group.md --}}
<div class="flex flex-col gap-8 rounded-lg border border-line bg-card p-4 text-ink">
    <div class="grid w-full max-w-xl gap-4">
        <x-ui::input label="Name" icon="user" value="Carlos André" description="How it shows up in reviews." />
        <x-ui::input type="email" label="Email" icon="at" value="carlos@" error="The domain after the @ is missing." />
    </div>

    <div class="grid w-full max-w-xl gap-4">
        <x-ui::input label="Webhook secret" value="whsec_8f2c4e1b9a91d" readonly copyable />
        <x-ui::input type="password" label="Password" value="secret-123" viewable />
        <x-ui::input placeholder="Search contacts" icon="search-01" kbd="⌘K" clearable />
        <x-ui::input label="Repository" placeholder="Available after connecting GitHub" disabled />
    </div>

    <div class="grid w-full max-w-md gap-3">
        <x-ui::input size="sm" placeholder="Small" />
        <x-ui::input placeholder="Medium (default)" />
        <x-ui::input size="lg" placeholder="Large" />
    </div>

    {{-- Variation: prefixes and attached selects --}}
    <div class="grid w-full max-w-xl gap-4">
        <x-ui::input.group>
            <x-ui::input.group.prefix>https://</x-ui::input.group.prefix>
            <x-ui::input placeholder="devaction.com" />
        </x-ui::input.group>

        <x-ui::input.group>
            <x-ui::input placeholder="Proposal amount" prefix="$" />
            <x-ui::select class="w-28"><option>USD</option><option>EUR</option></x-ui::select>
        </x-ui::input.group>

        <x-ui::select label="Pipeline stage" placeholder="Pick a stage">
            <x-ui::select.option value="lead">Lead</x-ui::select.option>
            <x-ui::select.option value="proposal">Proposal sent</x-ui::select.option>
            <x-ui::select.option value="negotiation">Negotiation</x-ui::select.option>
        </x-ui::select>
    </div>
</div>
