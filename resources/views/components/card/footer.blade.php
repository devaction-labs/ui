{{-- Rodapé do card: divisória em cima e ações à direita --}}
<div {{ $attributes->class(['flex flex-wrap items-center justify-end gap-2 border-t border-line-soft pt-4 text-[13px] text-muted']) }}>
    {{ $slot }}
</div>
