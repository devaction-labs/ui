{{-- Page header: heading with breadcrumbs, status, actions and tabs, plus a compact variant — see references/page-header.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::page-header heading="SGE · Church Management" description="Grace Community Church · M3 due Friday" class="w-full">
        <x-slot:breadcrumbs>
            <x-ui::breadcrumbs.item href="#">Projects</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.item>SGE</x-ui::breadcrumbs.item>
        </x-slot:breadcrumbs>
        <x-slot:status>
            <x-ui::tag color="ok" size="sm">Active</x-ui::tag>
        </x-slot:status>
        <x-slot:actions>
            <x-ui::button icon="download-04">Export</x-ui::button>
            <x-ui::button variant="primary" icon="add-01">New task</x-ui::button>
        </x-slot:actions>
        <x-slot:tabs>
            <x-ui::tabs>
                <x-ui::tab name="overview" selected>Overview</x-ui::tab>
                <x-ui::tab name="tasks" badge="12">Tasks</x-ui::tab>
                <x-ui::tab name="prs">PRs</x-ui::tab>
                <x-ui::tab name="contract">Contract</x-ui::tab>
            </x-ui::tabs>
        </x-slot:tabs>
    </x-ui::page-header>

    {{-- Variation: small size without breadcrumbs or tabs --}}
    <x-ui::page-header heading="Contacts" description="1,284 people in 312 companies" size="sm" class="w-full">
        <x-slot:actions>
            <x-ui::button variant="primary" icon="add-01">New contact</x-ui::button>
        </x-slot:actions>
    </x-ui::page-header>
</div>
