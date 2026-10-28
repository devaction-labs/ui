{{-- Kanban: four board columns with counts, WIP limits, tagged and overdue cards — see references/kanban.md --}}
@php
    $todo = [
        ['key' => '1', 'title' => 'Monthly treasurer report', 'due' => 'Fri 16', 'assignee' => 'Ana Souza'],
        ['key' => '2', 'title' => 'Checkout with Pix', 'due' => 'Oct 23', 'assignee' => 'Bruno Lima'],
    ];
    $doing = [
        ['key' => '3', 'title' => 'Fix N+1 in contributions', 'due' => 'yesterday', 'assignee' => 'Carla Dias', 'overdue' => true],
        ['key' => '4', 'title' => 'Cash reconciliation', 'due' => 'today', 'assignee' => 'Ana Souza', 'overdue' => false],
    ];
@endphp

<x-ui::kanban class="w-full">
    <x-ui::kanban.column id="todo" heading="To do" count="2" color="#A1A1AA">
        @foreach ($todo as $card)
            <x-ui::kanban.card :key="$card['key']" :due="$card['due']" :assignee="$card['assignee']">
                {{ $card['title'] }}
            </x-ui::kanban.card>
        @endforeach
        <x-slot:footer><x-ui::kanban.add>New task</x-ui::kanban.add></x-slot:footer>
    </x-ui::kanban.column>

    <x-ui::kanban.column id="doing" heading="In progress" count="2" color="#2563EB" limit="3">
        @foreach ($doing as $card)
            <x-ui::kanban.card :key="$card['key']" :due="$card['due']" :overdue="$card['overdue']" :assignee="$card['assignee']">
                {{ $card['title'] }}
                @if ($card['key'] === '3')
                    <x-slot:tags>
                        <x-ui::tag size="sm" color="risk">Bug</x-ui::tag>
                        <x-ui::tag size="sm" :uppercase="false">#142</x-ui::tag>
                    </x-slot:tags>
                @endif
            </x-ui::kanban.card>
        @endforeach
        <x-slot:footer><x-ui::kanban.add>New task</x-ui::kanban.add></x-slot:footer>
    </x-ui::kanban.column>

    <x-ui::kanban.column id="review" heading="In review" count="1" color="#D29A1E" limit="2">
        <x-ui::kanban.card key="5" assignee="Bruno Lima">
            Receipt uploads
            <x-slot:tags><x-ui::tag size="sm" color="warn" icon="sparkles">AI reviewed</x-ui::tag></x-slot:tags>
        </x-ui::kanban.card>
    </x-ui::kanban.column>

    <x-ui::kanban.column id="done" heading="Done" count="1" color="#16A34A">
        <x-ui::kanban.card key="7" done assignee="Ana Souza">Sign in with Google</x-ui::kanban.card>
    </x-ui::kanban.column>
</x-ui::kanban>
