{{--
    Divisória de um x-ui::resizable: arrastar, ← → (ou ↑ ↓ na vertical), Home/End e duplo clique para o tamanho inicial.

    <x-ui::resizable.handle label="Tamanho do diff" />
--}}
@props([
    'label' => 'Redimensionar painéis',
])

<div
    data-resizable-handle
    role="separator"
    tabindex="0"
    aria-label="{{ $label }}"
    x-bind:aria-orientation="vertical ? 'horizontal' : 'vertical'"
    x-bind:aria-valuenow="Math.round(size)"
    x-bind:aria-valuemin="min"
    x-bind:aria-valuemax="max"
    x-on:pointerdown="startDrag($event)"
    x-on:dblclick="reset()"
    x-on:keydown="onKeydown($event)"
    x-bind:class="vertical ? 'h-3 w-full cursor-row-resize border-y' : 'h-full w-3 cursor-col-resize border-x'"
    {{ $attributes->class(['group flex shrink-0 touch-none items-center justify-center border-line bg-subtle outline-none focus-visible:bg-wrap']) }}
>
    <span
        class="rounded-full bg-line-hover transition group-hover:bg-ink group-focus-visible:bg-ink group-active:bg-ink"
        x-bind:class="vertical ? 'h-1 w-8' : 'h-8 w-1'"
    ></span>
</div>
