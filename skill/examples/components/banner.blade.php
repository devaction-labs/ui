{{-- Banner: page-wide notice strip in info and warning variants — see references/banner.md --}}
<div class="flex w-full flex-col gap-2">
    <x-ui::banner heading="New:" text="Automatic review now runs on draft PRs." action="See changelog" href="#" />
    <x-ui::banner variant="warning" heading="GitHub API is unstable." text="Reviews may be delayed up to 10 minutes." action="GitHub status" href="#" />
</div>
