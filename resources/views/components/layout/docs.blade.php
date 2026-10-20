{{--
    Tema documentação: sidebar com as seções, texto no centro e o índice da página à direita.

    <x-ui::layout.docs>
        <x-slot:header><x-ui::logo size="xs">Docs</x-ui::logo></x-slot:header>
        <x-slot:sidebar>…links…</x-slot:sidebar>
        <x-slot:toc>
            <a href="#instalacao">Instalação</a>
        </x-slot:toc>
        …texto…
    </x-ui::layout.docs>
--}}
<div data-layout="docs" {{ $attributes->class(['flex min-h-screen flex-col bg-panel text-ink']) }}>
    @isset($header)
        <header class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b border-line bg-panel/90 px-4 backdrop-blur md:px-6">{{ $header }}</header>
    @endisset
    <div class="grid flex-1 lg:grid-cols-[260px_minmax(0,1fr)] xl:grid-cols-[260px_minmax(0,1fr)_232px]">
        <aside aria-label="{{ __('Sections') }}" class="border-r border-line max-lg:hidden">
            <nav class="sticky top-14 flex max-h-[calc(100vh-3.5rem)] flex-col gap-0.5 overflow-y-auto px-3.5 py-5 text-sm [&_a]:flex [&_a]:h-8 [&_a]:items-center [&_a]:rounded-lg [&_a]:px-2.5 [&_a]:text-ink-3 [&_a:hover]:bg-wrap [&_a[aria-current]]:bg-wrap [&_a[aria-current]]:font-medium [&_a[aria-current]]:text-ink">{{ $sidebar ?? '' }}</nav>
        </aside>
        <main class="min-w-0 px-4 py-8 md:px-12">
            <div class="mx-auto max-w-[760px] leading-[1.7] text-ink-2 [&_h1]:text-[40px] [&_h1]:font-semibold [&_h1]:tracking-[-0.03em] [&_h1]:text-ink [&_h2]:mt-10 [&_h2]:scroll-mt-20 [&_h2]:text-[22px] [&_h2]:font-semibold [&_h2]:text-ink [&_p]:mt-3">{{ $slot }}</div>
        </main>
        <aside aria-label="{{ __('On this page') }}" class="max-xl:hidden">
            <div class="sticky top-14 px-6 py-8 text-[13px]">
                <div class="font-mono text-[11px] tracking-[.08em] text-muted uppercase">{{ __('On this page') }}</div>
                <nav class="mt-2.5 flex flex-col gap-0.5 border-l border-line [&_a]:-ml-px [&_a]:border-l-2 [&_a]:border-transparent [&_a]:py-[5px] [&_a]:pl-3 [&_a]:text-muted [&_a:hover]:border-ink [&_a:hover]:text-ink">{{ $toc ?? '' }}</nav>
            </div>
        </aside>
    </div>
</div>
