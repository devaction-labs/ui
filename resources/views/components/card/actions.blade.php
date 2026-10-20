{{-- Ações do cabeçalho do card, alinhadas à direita --}}
<div data-card-actions {{ $attributes->class(['ml-auto flex shrink-0 items-center gap-2']) }}>
    {{ $slot }}
</div>
