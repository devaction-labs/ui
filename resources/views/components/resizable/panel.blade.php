{{-- Painel de um x-ui::resizable. O primeiro usa o tamanho da divisória; o segundo ocupa o resto. --}}
<div data-resizable-panel {{ $attributes->class(['min-h-0 min-w-0 overflow-auto p-4 first:shrink-0 first:grow-0 first:basis-(--resizable-size) last:flex-1']) }}>
    {{ $slot }}
</div>
