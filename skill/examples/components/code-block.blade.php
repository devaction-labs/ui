{{-- Code block: file header with syntax colors, plus Blade code with line numbers and highlighted lines — see references/code-block.md --}}
<div class="grid w-full gap-6">
    <x-ui::code-block filename="AppServiceProvider.php" language="php" class="w-full max-w-xl">
        public function boot(): void
        {
            Blaze::optimize()->in(resource_path('views/components'));
        }
    </x-ui::code-block>

    <x-ui::code-block filename="review.blade.php" language="blade" lines highlight="2-3" class="w-full max-w-xl">
        @verbatim
        <x-ui::callout variant="ai" icon="sparkles">
            <x-ui::callout.heading>The AI finished the review</x-ui::callout.heading>
            <x-ui::callout.text>2 issues and 3 suggestions on #142.</x-ui::callout.text>
        </x-ui::callout>
        @endverbatim
    </x-ui::code-block>
</div>
