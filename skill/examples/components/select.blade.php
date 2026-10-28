{{-- Select: native select with the kit look, a listbox with icons and metadata, and a multiple searchable listbox — see references/select.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::select label="Pipeline stage" placeholder="Choose a stage" class="max-w-sm">
        <x-ui::select.option value="lead">Lead</x-ui::select.option>
        <x-ui::select.option value="proposal">Proposal</x-ui::select.option>
        <x-ui::select.option value="negotiation">Negotiation</x-ui::select.option>
    </x-ui::select>

    {{-- Variation: listbox with icons and metadata, and a multiple searchable listbox with colors --}}
    <x-ui::select variant="listbox" label="Owner" placeholder="Choose someone" value="ana" class="max-w-sm">
        <x-ui::select.option value="ana" icon="user" meta="12 deals">Ana Souza</x-ui::select.option>
        <x-ui::select.option value="bruno" icon="user" meta="8 deals">Bruno Lima</x-ui::select.option>
        <x-ui::select.option value="carla" icon="user" meta="5 deals">Carla Dias</x-ui::select.option>
    </x-ui::select>

    <x-ui::select variant="listbox" searchable multiple label="Industries" placeholder="All industries" search-placeholder="Filter industries…" :value="['health', 'education']" class="max-w-sm">
        <x-ui::select.option value="health" color="#16A34A">Health</x-ui::select.option>
        <x-ui::select.option value="education" color="#2563EB">Education</x-ui::select.option>
        <x-ui::select.option value="retail" color="#C2850F">Retail</x-ui::select.option>
        <x-ui::select.option value="manufacturing" color="#7C3AED">Manufacturing</x-ui::select.option>
        <x-ui::select.option value="services" color="#DC2626">Services</x-ui::select.option>
    </x-ui::select>

    <x-ui::select variant="listbox" multiple :filter="false" wire:model.live="companyIds" label="Companies" placeholder="All companies" class="max-w-sm">
        <x-slot:input>
            <x-ui::select.input wire:model.live.debounce.250ms="companySearch" placeholder="Search company…" />
        </x-slot:input>
        <x-ui::select.option value="1" color="#2563EB">Acme Inc.</x-ui::select.option>
        <x-ui::select.option value="2" color="#16A34A">Smile Clinic</x-ui::select.option>
        <x-slot:empty>
            <x-ui::select.option.empty when-loading="Searching…">No companies found.</x-ui::select.option.empty>
        </x-slot:empty>
    </x-ui::select>
</div>
