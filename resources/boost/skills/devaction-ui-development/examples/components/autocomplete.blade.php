{{-- Autocomplete: free-text field with grouped suggestions, a badge and an AI action pinned at the end — see references/autocomplete.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::autocomplete placeholder="Search lessons and PRs…" class="max-w-md" value="ecto">
        <x-ui::autocomplete.group heading="Lessons">
            <x-ui::autocomplete.item value="Ecto.Multi and rollback" badge="30" />
            <x-ui::autocomplete.item value="Nested Ecto changesets" badge="62" />
            <x-ui::autocomplete.item value="LiveView streams" badge="14" />
        </x-ui::autocomplete.group>
        <x-ui::autocomplete.group heading="Pull requests">
            <x-ui::autocomplete.item value="Donations with Ecto.Multi" prefix="#142" />
            <x-ui::autocomplete.item value="Receipt uploads" prefix="#139" />
        </x-ui::autocomplete.group>
        <div class="mx-1 my-1.5 h-px bg-line-soft"></div>
        <x-ui::autocomplete.item always keep variant="ai" label="Ask the AI" kbd="⌘↵" />
    </x-ui::autocomplete>

    {{-- Variation: plain list with a labelled field and an empty-state message --}}
    <x-ui::autocomplete label="City" icon="building-03" placeholder="Start typing…" empty="No city suggested, you can type it anyway." class="max-w-md">
        <x-ui::autocomplete.item value="São Paulo" />
        <x-ui::autocomplete.item value="San Francisco" />
        <x-ui::autocomplete.item value="Santiago" />
        <x-ui::autocomplete.item value="Salvador" />
    </x-ui::autocomplete>
</div>
