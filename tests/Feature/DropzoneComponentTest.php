<?php

use Illuminate\Support\Facades\Blade;

test('dropzone wraps a native file input with drag handlers and upload progress', function () {
    $html = Blade::render('<x-ui::dropzone wire:model="attachments" multiple accept=".pdf" text="PDF até 25 MB" />');

    expect($html)
        ->toContain('data-dropzone')
        ->toContain('x-data="uiDropzone"')
        ->toContain('for="ui-dropzone-attachments"')
        ->toContain('type="file"')
        ->toContain('wire:model="attachments"')
        ->toContain('accept=".pdf"')
        ->toMatch('/type="file"[^>]*\smultiple/s')
        ->toContain('x-on:drop.prevent="drop($event)"')
        ->toContain('x-on:livewire-upload-progress=')
        ->toContain('data-icon="cloud-upload"')
        ->toContain('Arraste arquivos ou clique para escolher')
        ->toContain('PDF até 25 MB');
});

test('dropzone file shows each status', function (string $status, string $expected) {
    $html = Blade::render("<x-ui::dropzone.file name=\"relatorio.pdf\" size=\"1 MB\" status=\"{$status}\" :progress=\"40\" />");

    expect($html)
        ->toContain('data-status="'.$status.'"')
        ->toContain('>PDF</span>')
        ->toContain($expected);
})->with([
    ['done', 'Enviado'],
    ['uploading', '40%'],
    ['error', 'parou em 40%'],
    ['waiting', 'Na fila'],
    ['invalid', 'Tipo não aceito'],
]);

test('dropzone lists files and remove buttons below the area', function () {
    $html = Blade::render('<x-ui::dropzone><x-ui::dropzone.file name="a.png"><x-ui::dropzone.remove wire:click="remove(1)" /></x-ui::dropzone.file></x-ui::dropzone>');

    expect($html)
        ->toContain('data-dropzone-file')
        ->toContain('aria-label="Remover"')
        ->toContain('wire:click="remove(1)"');
});
