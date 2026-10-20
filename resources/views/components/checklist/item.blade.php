{{--
    Item do x-ui::checklist: checkbox nativo; wire:model e os outros atributos vão para o input.

    <x-ui::checklist.item wire:model.live="checks.recibo" description="Fernando · qui 15">Recibo depois do commit</x-ui::checklist.item>

    checked:     começa marcado (sem wire:model)
    description: texto menor à direita (responsável, prazo)
    Slot trailing: conteúdo à direita (avatar do responsável)
--}}
@props([
    'checked' => false,
    'description' => null,
])

<li data-checklist-item x-show="! (hide && $el.querySelector('input').checked)">
    <label class="group/check flex cursor-pointer items-center gap-2.5 rounded-lg px-1 py-1 transition hover:bg-subtle">
        <input type="checkbox" @checked($checked) {{ $attributes->class(['peer sr-only']) }} />
        <span aria-hidden="true" class="flex size-[17px] shrink-0 items-center justify-center rounded-[5px] border-[1.5px] border-line-hover bg-card text-on-primary transition peer-checked:border-primary peer-checked:bg-primary peer-focus-visible:ring-2 peer-focus-visible:ring-ring [&>svg]:opacity-0 peer-checked:[&>svg]:opacity-100">
            <x-ui::icon name="tick-02" class="size-3" />
        </span>
        <span class="min-w-0 flex-1 text-sm text-ink peer-checked:text-muted peer-checked:line-through">{{ $slot }}</span>
        @if ($description)
            <span class="shrink-0 text-xs text-muted">{{ $description }}</span>
        @endif
        @isset($trailing)
            <span class="shrink-0">{{ $trailing }}</span>
        @endisset
    </label>
</li>
