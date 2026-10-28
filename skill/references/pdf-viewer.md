---
kind: component
group: Data
components: [pdf-viewer, pdf-viewer.field, pdf-viewer.page, pdf-viewer.signer]
---
# PDF viewer · `<x-ui::pdf-viewer>`

A document to review and sign: thumbnails, zoom, pages and marked signature fields, with the signers panel.

## Examples

### Amendment to sign

Click the thumbnails or use ← → to change page; − and + change the zoom. "Go to my signature" opens the page with your field and focuses it.

```blade
<x-ui::pdf-viewer name="SGE contract · Amendment 02.pdf" download="#" class="h-[680px] w-full">
    @foreach ([
        ['AMENDMENT No. 02', 'Agreement for the development services of the Church Management System, entered into by Grace Community Church and DevAction Labs.'],
        ['CLAUSE ONE · SUBJECT', 'The financial reports module is added to the scope of milestone M3, as per the attached specification.'],
        ['CLAUSE TWO · TERM', 'The delivery term for milestone M3 becomes 30 calendar days from the signing of this amendment.'],
    ] as [$title, $text])
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
```

### Pages as images

For a real PDF, render one image per page on the server and pass it in src; the original file goes in download. Fields use % of the page, so they hold at any zoom.

```blade
<x-ui::pdf-viewer name="proposal.pdf" class="h-[420px] w-full max-w-3xl">
    <x-ui::pdf-viewer.page src="/apple-touch-icon.png" alt="Proposal page 1" />
</x-ui::pdf-viewer>
```

```php
// One image per page (spatie/pdf-to-image, Imagick or a conversion service)
$pages = collect(range(1, $pdf->pageCount()))
    ->map(fn (int $number) => Storage::url("contracts/{$contract->id}/page-{$number}.png"));

// <x-ui::pdf-viewer :download="Storage::url($contract->path)">
//     @foreach ($pages as $page) <x-ui::pdf-viewer.page :src="$page" /> @endforeach
// </x-ui::pdf-viewer>
```

## API

The kit does not ship a PDF reader (pdf.js): pages arrive as images or HTML, which keeps the page light and works without extra JavaScript. Clicking a field fires pdf-field with { signer, signed, mine }; wire:click on the field works too. Toolbar labels follow the app locale.

### `x-ui::pdf-viewer`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | string | `null` | File name in the toolbar. |
| `download` | string | `null` | Link to the original file (Download button). |
| `page · zoom` | int | `1 · 100` | Initial page and zoom (50 to 200%). |
| `slot aside` | slot | `—` | Panel on the right; goToMine() jumps to your signature field. |

### `x-ui::pdf-viewer.page`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `src · alt` | string | `null` | Page image; without src, the slot is the content. |

### `x-ui::pdf-viewer.field`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `x · y · w · h` | number | `0 · 0 · 30 · 10` | Position and size in % of the page. |
| `label · signer` | string | `Sign · null` | Field text and who signs. |
| `signed · mine` | boolean | `false` | Already signed (green) or your field (amber with a notice). |

### `x-ui::pdf-viewer.signer`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name · role · color` | string | `—` | Who signs and the avatar color. |
| `status` | string | `pending` | signed · pending · declined. |

## Accessibility

- Thumbnails are "Page N" buttons, with aria-current on the open page.
- ← → change page; zoom and current page are announced (aria-live).
- Signature fields are buttons with the signer's name; yours has the notice written out, not only the color.
