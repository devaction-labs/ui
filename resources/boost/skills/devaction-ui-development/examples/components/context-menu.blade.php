{{-- Context menu: right-click or Shift+F10 on a task list opens actions at the click point — see references/context-menu.md --}}
@php
    $tasks = [
        ['id' => 214, 'title' => 'Fix the financial report', 'done' => false],
        ['id' => 215, 'title' => 'Migrate old milestones with due_on', 'done' => true],
        ['id' => 216, 'title' => 'Review treasurer permissions', 'done' => false],
    ];
@endphp

{{-- Main usage: row list with a right-click menu --}}
<x-ui::context-menu class="w-full max-w-lg overflow-hidden rounded-xl border border-line text-sm">
    @foreach ($tasks as $task)
        <div tabindex="0" data-task-id="{{ $task['id'] }}" class="flex h-11 items-center gap-2.5 border-b border-line-soft px-3.5 outline-none last:border-b-0 focus:bg-wrap focus:outline-2 focus:-outline-offset-2 focus:outline-ink">
            <span class="size-4 rounded border border-line-hover"></span>
            <span class="{{ $task['done'] ? 'text-muted line-through' : 'text-ink' }}">{{ $task['title'] }}</span>
        </div>
    @endforeach

    <x-ui::menu>
        <x-ui::menu.item icon="view" kbd="↵">Open</x-ui::menu.item>
        <x-ui::menu.item icon="git-pull-request">View PR #214</x-ui::menu.item>
        <x-ui::menu.separator />
        <x-ui::menu.item icon="user-add-01" kbd="A" wire:click="assign">Assign to…</x-ui::menu.item>
        <x-ui::menu.item icon="copy-01" kbd="⌘C" href="#">Copy link</x-ui::menu.item>
        <x-ui::menu.separator />
        <x-ui::menu.item icon="delete-02" variant="danger" kbd="⌫" wire:click="delete">Delete task</x-ui::menu.item>
    </x-ui::menu>
</x-ui::context-menu>

{{-- Variation: disabled item and listening to context-menu-open to know the clicked row --}}
<div x-data="{ target: null }" x-on:context-menu-open.window="target = $event.detail.target" class="mt-4 text-sm text-muted">
    <p>Last right-clicked: <span x-text="target ? target.dataset.taskId : '—'" class="text-ink"></span></p>

    <x-ui::context-menu class="mt-2 w-full max-w-lg overflow-hidden rounded-xl border border-line">
        <div tabindex="0" class="flex h-11 items-center px-3.5 text-ink outline-none focus:bg-wrap focus:outline-2 focus:-outline-offset-2 focus:outline-ink">
            Archived task
        </div>

        <x-ui::menu>
            <x-ui::menu.item icon="view">Open</x-ui::menu.item>
            <x-ui::menu.item icon="archive-02" disabled>Restore (locked)</x-ui::menu.item>
        </x-ui::menu>
    </x-ui::context-menu>
</div>
