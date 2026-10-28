---
kind: template
group: Templates
components: [pricing, pricing.plan]
---
# Pricing · `<x-ui::pricing>`

A pricing page: cards with price, features and an action, plus a monthly / yearly billing toggle.

## Examples

### Plans

The price switches instantly between monthly and yearly. featured highlights the recommended plan; a text price ("Contact us") does not change with billing. The kit labels (Monthly, Yearly, "billed yearly") follow the app locale.

```blade
<x-ui::pricing heading="Plans" yearly-badge="2 months free" class="w-full">
    <x-ui::pricing.plan
        name="Freelancer"
        description="1 person, up to 3 repositories."
        :price="['monthly' => '$19', 'yearly' => '$15']"
        :features="['AI review on PRs', 'Projects and milestones', 'Mobile app']"
        cta="Get started"
        href="#"
    />
    <x-ui::pricing.plan
        name="Agency"
        description="For teams of up to 10 people."
        featured
        tag="Most popular"
        :price="['monthly' => '$79', 'yearly' => '$63']"
        :features="['Everything in Freelancer', 'Contracts and hour bank', 'Client portal', 'Unlimited repositories']"
        cta="Create agency account"
        href="#"
    />
    <x-ui::pricing.plan
        name="Custom"
        description="For networks and franchises."
        price="Contact us"
        :features="['Everything in Agency', 'SSO and audit log', 'Dedicated support']"
        cta="Talk to sales"
        href="#"
    />
</x-ui::pricing>
```

## API

wire:model on x-ui::pricing receives true (yearly) or false (monthly). wire:click and x-on:click attributes on a plan go to its button.

### `x-ui::pricing`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `yearly` | boolean | `true` | Starts on yearly; binds with wire:model. |
| `monthly-label · yearly-label` | string | `Monthly · Yearly` | Toggle labels (translated by the app locale). |
| `yearly-badge` | string | `null` | Green badge next to Yearly. |
| `heading` | string | `null` | Title to the left of the toggle. |
| `columns` | int | `3` | 2 · 3 · 4 columns from tablet up. |

### `x-ui::pricing.plan`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name · description` | string | `—` | Plan name and audience. |
| `price` | string \| array | `null` | Fixed text or ["monthly" => …, "yearly" => …]. |
| `per · note` | string | `/month · from billing` | Period and note below the price. |
| `features` | array | `[]` | Items with a ✓. |
| `cta · href` | string | `null` | Action button (a link with href). |
| `featured · tag` | boolean · string | `false · null` | Highlight and a label above the card. |

## Accessibility

- The toggle is a role="radiogroup" with aria-checked on each option.
- Each plan is an <article> with an <h3> heading.
- The highlight does not rely on color alone: it has a thicker border and a written label.
