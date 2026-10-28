{{-- Breadcrumb: page location trail with links, the current page and an icon-only item — see references/breadcrumb.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::breadcrumbs>
        <x-ui::breadcrumbs.item href="#">Today</x-ui::breadcrumbs.item>
        <x-ui::breadcrumbs.item href="#">SGE</x-ui::breadcrumbs.item>
        <x-ui::breadcrumbs.item>PR #142</x-ui::breadcrumbs.item>
    </x-ui::breadcrumbs>

    {{-- Variation: chevron separator, small size, icon-only items with aria-label --}}
    <x-ui::breadcrumbs separator="chevron" size="sm">
        <x-ui::breadcrumbs.item href="#" icon="home-01" aria-label="Home" />
        <x-ui::breadcrumbs.item icon="more-horizontal" aria-label="More levels" />
        <x-ui::breadcrumbs.item href="#">Contracts</x-ui::breadcrumbs.item>
        <x-ui::breadcrumbs.item>Amendment 02</x-ui::breadcrumbs.item>
    </x-ui::breadcrumbs>
</div>
