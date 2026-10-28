{{-- PDF viewer: contract pages with signature fields and a signers aside — see references/pdf-viewer.md --}}
@php
    $clauses = [
        ['AMENDMENT No. 02', 'Agreement for the development services of the Church Management System, entered into by Grace Community Church and DevAction Labs.'],
        ['CLAUSE ONE · SUBJECT', 'The financial reports module is added to the scope of milestone M3, as per the attached specification.'],
        ['CLAUSE TWO · TERM', 'The delivery term for milestone M3 becomes 30 calendar days from the signing of this amendment.'],
    ];
@endphp

<x-ui::pdf-viewer name="SGE contract · Amendment 02.pdf" download="/docs/contract.pdf" class="h-[680px] w-full">
    @foreach ($clauses as [$title, $text])
        <x-ui::pdf-viewer.page>
            <p class="text-center font-bold tracking-[0.04em]">{{ $title }}</p>
            <p class="mt-[1.2em]">{{ $text }}</p>
            <p class="mt-[0.8em]">The parties identified above agree to enter into this amendment, which is governed by the following clauses and by the terms of the original agreement, which remain unchanged.</p>
        </x-ui::pdf-viewer.page>
    @endforeach
    <x-ui::pdf-viewer.page>
        <p class="text-center font-bold tracking-[0.04em]">CLAUSE THREE · SIGNATURES</p>
        <p class="mt-[1.2em]">In witness whereof, the parties sign this amendment electronically.</p>
        <x-ui::pdf-viewer.field x="8" y="78" w="38" h="10" label="Signed" signer="Representative · Church" signed />
        <x-ui::pdf-viewer.field x="54" y="78" w="38" h="10" label="Sign" signer="Alex · DevAction Labs" mine />
    </x-ui::pdf-viewer.page>

    <x-slot:aside>
        <div class="font-semibold">Signatures</div>
        <x-ui::pdf-viewer.signer name="Representative" role="Grace Community Church" status="signed" color="red" />
        <x-ui::pdf-viewer.signer name="Alex" role="DevAction Labs" status="pending" color="ink" />
        <x-ui::callout variant="warning" text="1 signature of yours is missing, on page 4." />
        <x-ui::button variant="primary" class="mt-auto w-full" x-on:click="goToMine()">Go to my signature</x-ui::button>
    </x-slot:aside>
</x-ui::pdf-viewer>

{{-- Variation: single image page in a smaller viewer --}}
<x-ui::pdf-viewer name="proposal.pdf" class="mt-8 h-[420px] w-full max-w-3xl">
    <x-ui::pdf-viewer.page src="/apple-touch-icon.png" alt="Proposal page 1" />
</x-ui::pdf-viewer>
