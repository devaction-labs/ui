{{-- Command palette: ⌘K search with grouped actions and shortcuts — see references/command-palette.md --}}
@php
    $actions = [
        ['icon' => 'git-pull-request', 'description' => 'sge-api #214', 'kbd' => '↵', 'label' => 'Open PR review'],
        ['icon' => 'sparkles', 'description' => 'Requests a review now', 'kbd' => null, 'label' => 'Review with AI'],
        ['icon' => 'add-01', 'description' => 'In SGE · M3', 'kbd' => 'N', 'label' => 'New task'],
    ];

    $projects = ['SGE · Church Management', 'Hub API', 'AYRAM web'];
@endphp

{{-- Main: modal opened by ⌘K --}}
<x-ui::modal.trigger name="command-palette" shortcut="cmd.k">
    <x-ui::button icon="search-01">Search <x-ui::kbd class="ml-1">⌘K</x-ui::kbd></x-ui::button>
</x-ui::modal.trigger>

<x-ui::modal name="command-palette" variant="bare" size="lg">
    <x-ui::command class="w-full">
        <x-ui::command.input placeholder="Search or type a command…" />
        <x-ui::command.items>
            <x-ui::command.group heading="Actions">
                @foreach ($actions as $action)
                    <x-ui::command.item
                        :icon="$action['icon']"
                        :description="$action['description']"
                        :kbd="$action['kbd']"
                        wire:click="runAction"
                    >{{ $action['label'] }}</x-ui::command.item>
                @endforeach
            </x-ui::command.group>
            <x-ui::command.group heading="Projects">
                @foreach ($projects as $project)
                    <x-ui::command.item icon="folder-01" href="#">{{ $project }}</x-ui::command.item>
                @endforeach
            </x-ui::command.group>
            <x-ui::command.empty>Nothing found. Try another term.</x-ui::command.empty>
        </x-ui::command.items>
    </x-ui::command>
</x-ui::modal>

{{-- Variation: on the page, no modal, no close on pick, no key hint --}}
<x-ui::command class="w-full max-w-lg">
    <x-ui::command.input placeholder="Filter contacts" :kbd="false" />
    <x-ui::command.items>
        <x-ui::command.item icon="user" description="Baptist Church · client" :close="false">Pastor Elias Moura</x-ui::command.item>
        <x-ui::command.item icon="user" description="Hub API · technical" :close="false">Carlos André</x-ui::command.item>
        <x-ui::command.item icon="user" description="AYRAM · finance" :close="false">Fernanda Paiva</x-ui::command.item>
        <x-ui::command.empty>No contacts.</x-ui::command.empty>
    </x-ui::command.items>
</x-ui::command>
