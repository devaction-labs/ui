<?php

use Illuminate\Support\Facades\Blade;

test('pdf viewer renders pages, toolbar, signature fields and signers', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::pdf-viewer name="Aditivo 02.pdf" download="/contratos/2.pdf" zoom="125">
            <x-ui::pdf-viewer.page src="/p1.png" alt="Página 1" />
            <x-ui::pdf-viewer.page>
                <p>Assinaturas</p>
                <x-ui::pdf-viewer.field x="8" y="78" w="38" h="10" label="Assinado" signer="Igreja" signed />
                <x-ui::pdf-viewer.field x="54" y="78" w="38" h="10" signer="Alex" mine wire:click="openSignature" />
            </x-ui::pdf-viewer.page>
            <x-slot:aside>
                <x-ui::pdf-viewer.signer name="Alex" role="DevAction Labs" status="pending" />
            </x-slot:aside>
        </x-ui::pdf-viewer>
        BLADE);

    expect($html)
        ->toContain('data-pdf-viewer')
        ->toContain('uiPdfViewer({ page: 1, zoom: 125 })')
        ->toContain('Aditivo 02.pdf')
        ->toContain('href="/contratos/2.pdf"')
        ->toContain('aria-label="Diminuir zoom"')
        ->toContain('src="/p1.png" alt="Página 1"')
        ->toContain('left: 54%; top: 78%; width: 38%; height: 10%')
        ->toContain('VOCÊ ASSINA AQUI')
        ->toContain('data-mine')
        ->toContain('wire:click="openSignature"')
        ->toContain('border-ok-line bg-ok-bg')
        ->toContain('Pendente')
        ->toContain('lg:grid-cols-[150px_minmax(0,1fr)_280px]')
        ->and(substr_count($html, 'data-pdf-page'))->toBe(2);
});
