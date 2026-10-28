{{-- Combobox: searchable single and multiple select, with a server-side search and a create option — see references/combobox.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::combobox label="Project" value="igreja" search-placeholder="Filter projects…" empty="No project found." class="max-w-sm">
        <x-ui::combobox.option value="igreja" color="#2563EB" meta="12 PRs">Igreja Viva</x-ui::combobox.option>
        <x-ui::combobox.option value="loja" color="#16A34A" meta="4 PRs">Ponto Store</x-ui::combobox.option>
        <x-ui::combobox.option value="clinica" color="#C2850F" meta="7 PRs">Sorriso Clínica</x-ui::combobox.option>
        <x-ui::combobox.option value="escola" color="#7C3AED" meta="2 PRs">Aprender School</x-ui::combobox.option>
        <x-ui::combobox.option value="academia" color="#DC2626" meta="archived" disabled>Forte Gym</x-ui::combobox.option>
    </x-ui::combobox>

    {{-- Variation: multiple selection with preselected values --}}
    <x-ui::combobox multiple label="Projects" placeholder="All projects" :value="['igreja', 'clinica']" class="max-w-sm">
        <x-ui::combobox.option value="igreja" color="#2563EB">Igreja Viva</x-ui::combobox.option>
        <x-ui::combobox.option value="loja" color="#16A34A">Ponto Store</x-ui::combobox.option>
        <x-ui::combobox.option value="clinica" color="#C2850F">Sorriso Clínica</x-ui::combobox.option>
        <x-ui::combobox.option value="escola" color="#7C3AED">Aprender School</x-ui::combobox.option>
    </x-ui::combobox>

    <x-ui::combobox wire:model.live="companyId" :filter="false" label="Company" placeholder="Choose the company" class="max-w-sm">
        <x-slot:input>
            <x-ui::combobox.input wire:model.live.debounce.250ms="companySearch" placeholder="Search company…" />
        </x-slot:input>
        <x-ui::combobox.option value="1" color="#2563EB">Acme Inc.</x-ui::combobox.option>
        <x-ui::combobox.option value="2" color="#16A34A">Sorriso Clínica</x-ui::combobox.option>
        <x-ui::combobox.option.create wire:click="createCompany" min-length="3">
            Create "<span wire:text="companySearch"></span>"
        </x-ui::combobox.option.create>
        <x-slot:empty>
            <x-ui::combobox.option.empty when-loading="Searching…">No company found.</x-ui::combobox.option.empty>
        </x-slot:empty>
    </x-ui::combobox>
</div>
