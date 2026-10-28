{{-- Docs layout: navigation, article and table of contents for docs and knowledge bases — see references/layouts.md --}}
<x-ui::layout.docs>
    <x-slot:header>
        <div class="flex items-center gap-2.5">
            <x-ui::logo size="xs">Acme</x-ui::logo>
            <span class="text-sm font-medium text-ink">Acme Docs</span>
        </div>
    </x-slot:header>

    <x-slot:sidebar>
        <nav aria-label="Docs" class="flex flex-col gap-1 text-sm">
            <a href="#" aria-current="page" class="rounded-lg bg-card px-3 py-1.5 font-medium text-ink">Getting started</a>
            <a href="#" class="rounded-lg px-3 py-1.5 text-muted hover:text-ink">Installation</a>
            <a href="#" class="rounded-lg px-3 py-1.5 text-muted hover:text-ink">Configuration</a>
        </nav>
    </x-slot:sidebar>

    <x-slot:toc>
        <a href="#install" class="block py-1 text-sm text-muted hover:text-ink">Install</a>
        <a href="#configure" class="block py-1 text-sm text-muted hover:text-ink">Configure</a>
    </x-slot:toc>

    <article class="flex flex-col gap-4">
        <h1 class="text-3xl font-semibold tracking-[-0.02em] text-ink">Getting started</h1>
        <p class="text-muted">Install the kit in a new project, then render your first page in a few minutes.</p>

        <h2 id="install" class="mt-4 text-xl font-semibold text-ink">Install</h2>
        <p class="text-muted">Add the package with Composer and publish the views when you need to change them.</p>

        <h2 id="configure" class="mt-4 text-xl font-semibold text-ink">Configure</h2>
        <p class="text-muted">Set the theme in your layout and keep the brand name in one place.</p>
    </article>
</x-ui::layout.docs>
