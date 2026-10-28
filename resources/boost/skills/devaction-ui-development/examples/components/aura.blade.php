{{-- Aura: light border around cards while the AI works, with glow, sizes and a paused state — see references/aura.md --}}
<div class="grid w-full max-w-md gap-6">
    <x-ui::aura>
        <div role="status" class="flex items-center gap-3 rounded-[14px] bg-card px-4 py-3.5">
            <x-ui::spinner class="text-ai" />
            <span class="flex flex-1 flex-col text-sm"><strong class="font-semibold text-ink">Reviewing PR #214</strong><span class="text-muted">12 of 31 files</span></span>
        </div>
    </x-ui::aura>

    {{-- Variation: dual-tone aura around a lesson card --}}
    <x-ui::aura variant="dual" size="lg">
        <div class="flex items-center gap-3 rounded-[14px] bg-card px-4 py-3.5">
            <span class="flex size-[34px] items-center justify-center rounded-[10px] bg-info-bg text-info-fg"><x-ui::icon name="book-02" class="size-[18px]" /></span>
            <span class="flex flex-1 flex-col text-sm"><strong class="font-semibold text-ink">Writing a new lesson</strong><span class="text-muted">Ecto.Multi and rollback</span></span>
        </div>
    </x-ui::aura>
</div>

<div class="mt-8 flex w-full flex-wrap items-center gap-5">
    <x-ui::aura variant="glow" color="risk">
        <div role="alert" class="flex items-center gap-3 rounded-[14px] bg-card px-4 py-3.5 text-sm">
            <strong class="font-semibold text-ink">PR #145 is out of scope</strong>
            <x-ui::button size="sm" variant="primary">Draft amendment</x-ui::button>
        </div>
    </x-ui::aura>
    <x-ui::aura :active="false" radius="12px"><x-ui::button class="rounded-xl">Still</x-ui::button></x-ui::aura>
</div>
