{{--
    Botão de remover um arquivo da fila.

    <x-ui::dropzone.remove wire:click="removeAttachment(3)" />
    <x-ui::dropzone.remove icon="delete-02" label="Excluir" />
--}}
@props([
    'icon' => 'cancel-01',
    'label' => null,
])

@php
    $label ??= __('Remove');
@endphp

<button
    type="button"
    aria-label="{{ $label }}"
    {{ $attributes->class(['inline-flex size-7 flex-none cursor-pointer items-center justify-center rounded-lg text-muted transition hover:bg-wrap hover:text-ink']) }}
>
    <x-ui::icon :name="$icon" class="size-4" />
</button>
