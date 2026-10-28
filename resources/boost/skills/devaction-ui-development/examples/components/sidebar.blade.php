{{-- Sidebar: app navigation with brand, search, items, an expandable group and a profile footer — see references/sidebar.md --}}
<div class="flex h-[620px] w-full flex-col overflow-hidden rounded-[18px] bg-frame md:flex-row md:gap-3.5 md:p-3.5">
    <x-ui::sidebar :sticky="false">
        <x-slot:brand>
            <x-ui::sidebar.brand name="DevAction IA" href="#" />
        </x-slot:brand>
        <x-ui::sidebar.search />
        <x-ui::sidebar.nav>
            <x-ui::sidebar.item icon="home-01" href="#">Today</x-ui::sidebar.item>
            <x-ui::sidebar.item icon="folder-01" href="#" current>Projects</x-ui::sidebar.item>
            <x-ui::sidebar.item icon="file-01" href="#">Contracts</x-ui::sidebar.item>
            <x-ui::sidebar.item icon="git-pull-request" href="#" badge="4">Pull Requests</x-ui::sidebar.item>
        </x-ui::sidebar.nav>
        <x-ui::sidebar.group heading="Account" expandable>
            <x-ui::sidebar.item icon="book-02" href="#">Learning</x-ui::sidebar.item>
            <x-ui::sidebar.item icon="settings-02" href="#">Settings</x-ui::sidebar.item>
        </x-ui::sidebar.group>
        <x-ui::sidebar.spacer />
        <x-ui::sidebar.profile name="Alex Nogueira" description="DevAction Labs" />
    </x-ui::sidebar>

    <x-ui::main>
        <x-ui::page-header heading="SGE · Church Management" description="Campo Limpo Baptist Church · M3 due Friday" size="sm" />
    </x-ui::main>
</div>
