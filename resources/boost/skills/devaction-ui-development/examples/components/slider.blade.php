{{-- Slider: single and range sliders with value bubble, marks and units — see references/slider.md --}}
<div class="flex flex-col gap-8 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::slider label="Minimum test coverage" value="70" unit="%" bubble class="max-w-md pt-6" />

    {{-- Variation: named marks, and a range with a minimum distance between thumbs --}}
    <x-ui::slider label="Review strictness" min="0" max="3" value="1" :marks="['Light', 'Standard', 'Strict', 'Maximum']" class="max-w-md" />

    <x-ui::slider label="Contract value" range min="0" max="100" step="5" min-distance="10" :value="[20, 70]" prefix="$" unit="k" class="max-w-md" />
</div>
