{{-- Top bar layout: horizontal navigation for a client portal with up to six sections — see references/layouts.md --}}
<x-ui::layout.topbar width="1280px">
    <x-slot:brand><x-ui::logo size="xs">Acme</x-ui::logo></x-slot:brand>
    <x-slot:nav>
        <x-ui::layout.topbar.item href="#" icon="folder-01" current>Projects</x-ui::layout.topbar.item>
        <x-ui::layout.topbar.item href="#" icon="invoice-01">Invoices</x-ui::layout.topbar.item>
        <x-ui::layout.topbar.item href="#" icon="file-01">Documents</x-ui::layout.topbar.item>
    </x-slot:nav>
    <x-slot:actions>
        <x-ui::avatar name="Paula Ramos" size="sm" circle />
    </x-slot:actions>

    <div class="mx-auto w-full max-w-5xl px-4 py-8 md:px-6">
        <x-ui::page-header heading="Projects" description="Shared with you by Acme">
            <x-slot:actions>
                <x-ui::button variant="primary" icon="folder-add">Request project</x-ui::button>
            </x-slot:actions>
        </x-ui::page-header>

        <div class="mt-6 grid gap-3 md:grid-cols-2">
            <div class="rounded-xl border border-line bg-card p-4">
                <span class="text-sm font-medium text-ink">Website refresh</span>
                <p class="mt-1 text-sm text-muted">Milestone 2 of 3 is due on Friday.</p>
            </div>
            <div class="rounded-xl border border-line bg-card p-4">
                <span class="text-sm font-medium text-ink">Mobile app beta</span>
                <p class="mt-1 text-sm text-muted">Waiting for your feedback on the build.</p>
            </div>
        </div>
    </div>
</x-ui::layout.topbar>
