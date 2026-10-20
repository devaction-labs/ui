{{--
    Kanban: colunas lado a lado; arrastar entre elas usa o wire:sort do Livewire 4.
    As colunas dividem a largura (mínimo de 260 px) e rolam para o lado quando não cabem.

    <x-ui::kanban>
        @foreach ($stages as $stage)
            <x-ui::kanban.column :id="$stage->id" :heading="$stage->name" :count="$stage->deals->count()" color="#2563EB" sort="moveDeal">
                @foreach ($stage->deals as $deal)
                    <x-ui::kanban.card :key="$deal->id" :due="$deal->due" :assignee="$deal->owner->name">{{ $deal->title }}</x-ui::kanban.card>
                @endforeach
                <x-slot:footer><x-ui::kanban.add wire:click="createDeal({{ $stage->id }})">Novo negócio</x-ui::kanban.add></x-slot:footer>
            </x-ui::kanban.column>
        @endforeach
    </x-ui::kanban>
--}}
<div data-kanban {{ $attributes->class(['flex items-start gap-3.5 overflow-x-auto pb-2']) }}>
    {{ $slot }}
</div>
