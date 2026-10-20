# Money input · `<x-ui::money-input>`

Money amount: formats the currency while you type and sends the raw number to Livewire and to the form.

## Examples

### Milestone amount

Type digits only: the field fills from the cents. suffix explains the billing unit.

```blade
<div class="grid w-full max-w-md gap-4">
    <x-ui::money-input label="Milestone amount" currency="USD" locale="en-US" value="18500" suffix="per milestone" />
    <x-ui::money-input label="Hourly rate" currency="USD" locale="en-US" value="220" suffix="per hour" description="Used in the hour bank." />
</div>
```

### Other currencies and error

currency and locale change the symbol and the format. The error comes from wire:model.

```blade
<div class="grid w-full max-w-md gap-4">
    <x-ui::money-input label="Annual license" currency="EUR" locale="de-DE" value="1250.5" />
    <x-ui::money-input label="Discount" currency="BRL" locale="pt-BR" error="The discount cannot exceed the contract amount." />
</div>
```

## API

wire:model, x-model and name go to a hidden input, which receives the number with a dot (1234.56). The other attributes go to the visible field.

### `x-ui::money-input`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description · error` | string | `null` | Same as the other fields. |
| `value` | number | `null` | Initial value, in units (18500 = $18,500.00). |
| `currency` | BRL \| USD \| EUR | `BRL` | Sets the symbol. |
| `symbol` | string | `from currency` | Custom symbol. |
| `locale` | string | `pt-BR` | Thousands and decimal format. |
| `decimals` | int | `2` | Decimal places. |
| `suffix` | string | `null` | Add-on on the right, like "per milestone". |

## Accessibility

- The visible field has a label with the currency ("Milestone amount in $").
- inputmode="numeric" opens the numeric keyboard on phones.
- The formatted value is what people see; the raw number goes hidden to the server.
- Errors set aria-invalid and the message is linked with aria-describedby.
