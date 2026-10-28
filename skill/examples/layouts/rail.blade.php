{{-- Rail layout: classic collapsed to icons only, for dense screens like diffs and kanban (⌘B toggles) — see references/layouts.md --}}
@php
    $changes = [
        ['file' => 'app/Billing/Invoice.php', 'lines' => '+12 −3'],
        ['file' => 'app/Billing/Refund.php', 'lines' => '+4 −9'],
        ['file' => 'tests/Feature/InvoiceTest.php', 'lines' => '+31 −0'],
    ];
@endphp

<x-ui::layout.classic collapsed>
    <x-slot:sidebar>
        <x-ui::sidebar>
            <x-slot:brand><x-ui::sidebar.brand name="Acme" href="#" /></x-slot:brand>
            <x-ui::sidebar.nav>
                <x-ui::sidebar.item icon="home-01" href="#" current>Today</x-ui::sidebar.item>
                <x-ui::sidebar.item icon="folder-01" href="#">Projects</x-ui::sidebar.item>
                <x-ui::sidebar.item icon="invoice-01" href="#">Invoices</x-ui::sidebar.item>
            </x-ui::sidebar.nav>
            <x-ui::sidebar.spacer />
            <x-ui::sidebar.profile name="Alex" description="alex@acme.com" />
        </x-ui::sidebar>
    </x-slot:sidebar>

    <x-slot:header>
        <x-ui::breadcrumbs size="sm">
            <x-ui::breadcrumbs.item href="#">Reviews</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.item>PR #142</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot:header>
    <x-slot:actions>
        <x-ui::button size="sm" variant="ghost" icon="notification-01" aria-label="Notifications" />
    </x-slot:actions>

    <x-ui::page-header heading="Changes in PR #142" description="3 files changed" />

    <div class="flex flex-col gap-2">
        @foreach ($changes as $change)
            <div class="flex items-center justify-between gap-3 rounded-xl border border-line bg-card px-4 py-3">
                <span class="font-mono text-sm text-ink">{{ $change['file'] }}</span>
                <span class="text-xs text-muted">{{ $change['lines'] }}</span>
            </div>
        @endforeach
    </div>
</x-ui::layout.classic>
