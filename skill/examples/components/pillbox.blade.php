{{-- Pillbox: multiple values shown as pills in a field, with suggestions and a combobox variant — see references/pillbox.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::pillbox label="Tags" placeholder="Pick the tags" searchable search-placeholder="Search tags…" :value="['vip']" class="max-w-md">
        <x-ui::pillbox.option value="vip" icon="star">VIP</x-ui::pillbox.option>
        <x-ui::pillbox.option value="referral" icon="user-add-01">Referral</x-ui::pillbox.option>
        <x-ui::pillbox.option value="partner" icon="handshake">Partner</x-ui::pillbox.option>
        <x-ui::pillbox.option value="overdue" icon="alert-02">Overdue</x-ui::pillbox.option>
    </x-ui::pillbox>

    {{-- Variation: combobox variant with a free-text input, server-side search and create option --}}
    <x-ui::pillbox wire:model.live="selectedTags" variant="combobox" :filter="false" label="Tags" class="max-w-md">
        <x-slot:input>
            <x-ui::pillbox.input wire:model.live.debounce.250ms="search" placeholder="Pick the tags…" />
        </x-slot:input>
        <x-ui::pillbox.option value="1">VIP</x-ui::pillbox.option>
        <x-ui::pillbox.option value="2">Referral</x-ui::pillbox.option>
        <x-ui::pillbox.option.create wire:click="createTag" min-length="2">
            Create "<span wire:text="search"></span>"
        </x-ui::pillbox.option.create>
    </x-ui::pillbox>

    <x-ui::pillbox
        label="Contact tags"
        placeholder="Add a tag…"
        :value="['Customer', 'Referral']"
        :suggestions="['Customer', 'Hot lead', 'Cold lead', 'Referral', 'Partner', 'Overdue', 'VIP']"
        max="5"
        description="Up to 5 tags."
        class="max-w-md"
    />
</div>
