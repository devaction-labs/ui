{{-- Lista de opções da paleta, com rolagem até 420 px --}}
<div
    role="listbox"
    data-command-items
    {{ $attributes->class(['flex max-h-[420px] flex-col overflow-y-auto px-2 pt-1.5 pb-2']) }}
>
    {{ $slot }}
</div>
