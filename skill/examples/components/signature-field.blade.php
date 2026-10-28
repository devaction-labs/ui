{{-- Signature field: draw or type a signature, with an audit note — see references/signature-field.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::signature-field
        label="Amendment signature"
        note="Along with the image we store date, time, IP and the PDF hash."
        class="w-full max-w-md"
    />

    {{-- Variation: typed signature mode with a signer name --}}
    <x-ui::signature-field mode="type" signer="Ana Souza" class="w-full max-w-md" />
</div>
