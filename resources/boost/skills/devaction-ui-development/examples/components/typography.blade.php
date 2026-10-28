{{-- Typography: headings, body text and eyebrow labels at their levels and sizes — see references/typography.md --}}
<div class="flex flex-col gap-4 text-ink">
    <x-ui::eyebrow>How it works</x-ui::eyebrow>
    <x-ui::heading level="1" size="xl">Contracts</x-ui::heading>
    <x-ui::text class="mt-2">Change your personal details.</x-ui::text>

    <x-ui::heading level="2" size="lg">Update profile</x-ui::heading>
    <x-ui::text variant="strong">Total: $1,290</x-ui::text>
    <x-ui::text size="sm" variant="subtle">Updated 2 minutes ago</x-ui::text>

    {{-- Variation: accent eyebrow above a default-level heading --}}
    <x-ui::eyebrow accent>Web, mobile and tablet</x-ui::eyebrow>
    <x-ui::heading>Billing</x-ui::heading>
</div>
