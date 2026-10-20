{{--
    Visualizador de documento (contrato, aditivo, proposta) com miniaturas, zoom e campos de assinatura.
    As páginas vêm prontas: imagens geradas no servidor (ex.: spatie/pdf-to-image) ou HTML. O PDF original vai no download.

    <x-ui::pdf-viewer name="Contrato SGE · Aditivo 02.pdf" download="/contratos/2.pdf">
        <x-ui::pdf-viewer.page src="/contratos/2/pagina-1.png" />
        <x-ui::pdf-viewer.page src="/contratos/2/pagina-4.png">
            <x-ui::pdf-viewer.field x="8" y="80" w="38" h="10" label="Assinado" signer="Responsável · Igreja" signed />
            <x-ui::pdf-viewer.field x="54" y="80" w="38" h="10" label="Assinar" signer="Alex · DevAction Labs" mine wire:click="openSignature" />
        </x-ui::pdf-viewer.page>
        <x-slot:aside>…</x-slot:aside>
    </x-ui::pdf-viewer>

    page:  página inicial (padrão 1) · zoom: % inicial (padrão 100; de 50 a 200)
    aside: painel à direita (ex.: assinaturas com x-ui::pdf-viewer.signer e o botão goToMine())
--}}
@props([
    'name' => null,
    'download' => null,
    'page' => 1,
    'zoom' => 100,
])

<section
    data-pdf-viewer
    aria-label="{{ $name ?? __('Document') }}"
    x-data="uiPdfViewer({ page: {{ (int) $page }}, zoom: {{ (int) $zoom }} })"
    x-on:keydown.left="if (! $event.target.closest('input, textarea, [contenteditable]')) previous()"
    x-on:keydown.right="if (! $event.target.closest('input, textarea, [contenteditable]')) next()"
    {{ $attributes->class(['grid overflow-hidden rounded-2xl border border-line bg-card text-ink', 'lg:grid-cols-[150px_minmax(0,1fr)_280px]' => isset($aside), 'md:grid-cols-[150px_minmax(0,1fr)]' => ! isset($aside)]) }}
>
    <nav aria-label="{{ __('Pages') }}" class="flex gap-3 overflow-auto border-line bg-subtle p-3 max-md:hidden md:flex-col md:border-r">
        <template x-for="(thumb, index) in thumbs" x-bind:key="index">
            <button
                type="button"
                x-on:click="go(index + 1)"
                x-bind:aria-current="current === index + 1 ? 'page' : null"
                x-bind:aria-label="@js(__('Page :page')).replace(':page', index + 1)"
                class="flex shrink-0 cursor-pointer flex-col items-center gap-1.5"
            >
                <span
                    class="relative flex h-[124px] w-24 flex-col gap-1 overflow-hidden rounded-md border-2 bg-white px-[9px] py-2.5"
                    x-bind:class="current === index + 1 ? 'border-ink' : 'border-line'"
                >
                    <template x-if="thumb.src"><img x-bind:src="thumb.src" alt="" class="absolute inset-0 size-full object-cover" /></template>
                    <template x-if="! thumb.src">
                        <span class="contents">
                            <span class="h-1 w-3/5 rounded-full bg-line-hover"></span>
                            <span class="h-[3px] w-full rounded-full bg-line"></span>
                            <span class="h-[3px] w-11/12 rounded-full bg-line"></span>
                            <span class="h-[3px] w-full rounded-full bg-line"></span>
                            <span class="h-[3px] w-2/3 rounded-full bg-line"></span>
                        </span>
                    </template>
                    <span x-show="thumb.fields" class="absolute bottom-3 left-[9px] h-3.5 w-11 rounded-[3px] border border-dashed border-ai bg-warn-bg"></span>
                </span>
                <span class="text-xs" x-bind:class="current === index + 1 ? 'text-ink' : 'text-muted'" x-text="index + 1"></span>
            </button>
        </template>
    </nav>

    <div class="flex min-w-0 flex-col bg-line">
        <div class="flex h-12 items-center gap-2 border-b border-line bg-card px-3 text-[13px]">
            <span class="min-w-0 truncate font-medium">{{ $name }}</span>
            <span class="ml-auto flex items-center gap-1">
                <x-ui::button size="xs" x-on:click="zoomOut()" :aria-label="__('Zoom out')" x-bind:disabled="zoom <= 50">−</x-ui::button>
                <span class="w-[52px] text-center font-mono text-xs" x-text="zoom + '%'" aria-live="polite">{{ (int) $zoom }}%</span>
                <x-ui::button size="xs" x-on:click="zoomIn()" :aria-label="__('Zoom in')" x-bind:disabled="zoom >= 200">+</x-ui::button>
            </span>
            <x-ui::separator vertical />
            <x-ui::button size="xs" variant="ghost" icon="arrow-left-01" x-on:click="previous()" :aria-label="__('Previous page')" x-bind:disabled="current <= 1" />
            <span class="font-mono text-xs text-ink-3" x-text="current + ' / ' + total" aria-live="polite"></span>
            <x-ui::button size="xs" variant="ghost" icon="arrow-right-01" x-on:click="next()" :aria-label="__('Next page')" x-bind:disabled="current >= total" />
            @if ($download)
                <x-ui::button size="xs" icon="download-04" :href="$download" download :aria-label="__('Download')" />
            @endif
        </div>
        <div class="flex min-h-[420px] flex-1 justify-center overflow-auto p-6" x-ref="stage" tabindex="0" aria-label="{{ __('Document page') }}">
            {{ $slot }}
        </div>
    </div>

    @isset($aside)
        <aside class="flex flex-col gap-3.5 border-line p-4 text-sm max-lg:border-t lg:border-l">
            {{ $aside }}
        </aside>
    @endisset
</section>
