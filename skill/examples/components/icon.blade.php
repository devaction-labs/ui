{{-- Icon: Hugeicons by name, sized and colored with classes, plus an icon with a text label — see references/icon.md --}}
<div class="flex flex-wrap items-center gap-4 text-ink">
    <x-ui::icon name="sparkles" />
    <x-ui::icon name="git-pull-request" class="size-4 text-muted" />

    {{-- Variation: named icon exposed to assistive technology with a label --}}
    <x-ui::icon name="github" label="GitHub" />
</div>
