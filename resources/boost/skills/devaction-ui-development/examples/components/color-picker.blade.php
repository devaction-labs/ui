{{-- Color picker: field with a swatch and a popover, plus an inline variant with alpha — see references/color-picker.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::color-picker label="Project color" value="#2563EB" class="max-w-xs" />

    {{-- Variation: inline picker with the alpha channel enabled --}}
    <x-ui::color-picker inline alpha value="#7C3AED" />
</div>
