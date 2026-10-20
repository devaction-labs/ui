{{--
    Botão de adicionar no fim da coluna.

    <x-ui::kanban.add wire:click="createTask('todo')">Nova tarefa</x-ui::kanban.add>
--}}
<button
    type="button"
    data-kanban-add
    wire:sort:ignore
    {{ $attributes->class(['flex h-9 cursor-pointer items-center gap-1.5 rounded-[10px] px-1.5 text-left text-[13px] text-ink-3 transition hover:bg-line-soft hover:text-ink']) }}
>
    <x-ui::icon name="add-01" class="size-4" />
    {{ $slot->isEmpty() ? __('Add') : $slot }}
</button>
