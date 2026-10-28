{{-- Pricing: three plans with a monthly and yearly toggle, each CTA submits a POST form — see references/pricing.md --}}
<form method="POST" action="#" class="w-full">
    @csrf
    <x-ui::pricing heading="Plans" yearly-badge="2 months free" class="w-full">
        <x-ui::pricing.plan
            name="Freelancer"
            description="1 person, up to 3 repositories."
            :price="['monthly' => '$19', 'yearly' => '$15']"
            :features="['AI review on PRs', 'Projects and milestones', 'Mobile app']"
            cta="Get started"
        />
        <x-ui::pricing.plan
            name="Agency"
            description="For teams of up to 10 people."
            featured
            tag="Most popular"
            :price="['monthly' => '$79', 'yearly' => '$63']"
            :features="['Everything in Freelancer', 'Contracts and hour bank', 'Client portal']"
            cta="Create agency account"
        />
        <x-ui::pricing.plan
            name="Custom"
            description="For networks and franchises."
            price="Contact us"
            :features="['Everything in Agency', 'SSO and audit log', 'Dedicated support']"
            cta="Talk to sales"
        />
    </x-ui::pricing>
</form>
