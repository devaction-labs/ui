# Avatar · `<x-ui::avatar>`

Represents people, workspaces, repositories, clients and the AI agent. Initials come from the name and the color is stable by hash.

## Examples

### Sizes

base (40 px) is the default. xs and sm in dense lists and groups; lg and xl in profiles.

```blade
<x-ui::avatar name="Alex Nogueira" color="indigo" size="xs" circle />
<x-ui::avatar name="Alex Nogueira" color="indigo" size="sm" circle />
<x-ui::avatar name="Alex Nogueira" color="indigo" circle />
<x-ui::avatar name="Alex Nogueira" color="indigo" size="lg" circle />
<x-ui::avatar name="Alex Nogueira" color="indigo" size="xl" circle />
```

### Automatic color

color="auto" picks the color from the name, always the same for the same person. Use color:seed with the id so it does not change when the name changes.

```blade
<x-ui::avatar name="Alex Nogueira" color="auto" circle />
<x-ui::avatar name="Carlos André" color="auto" circle />
<x-ui::avatar name="Fernanda Paiva" color="auto" circle />
<x-ui::avatar name="Marina Souza" color="auto" circle />
<x-ui::avatar name="Baptist Church" color="auto" color:seed="client-42" />
```

### Status

badge alone becomes the status dot. badge:label tells screen readers the status.

```blade
<x-ui::avatar name="Alex Nogueira" color="indigo" circle badge badge:color="green" badge:label="Online" />
<x-ui::avatar name="Carlos André" color="emerald" circle badge badge:color="amber" badge:label="Away" />
<x-ui::avatar name="Fernanda Paiva" color="red" circle badge badge:color="red" badge:label="Busy" />
<x-ui::avatar name="Marina Souza" circle badge badge:label="Offline" />
<x-ui::avatar name="Alex Nogueira" color="indigo" circle badge="3" badge:color="red" badge:position="top right" badge:circle />
```

### Group

avatar.group overlaps the avatars with a ring in the card color. max collapses the rest into a +N; each avatar’s badge shows who is online.

```blade
<div class="flex flex-col gap-4">
    <x-ui::avatar.group size="base" label="SGE team · 2 online">
        <x-ui::avatar name="Alex Nogueira" color="ink" circle badge badge:color="green" badge:label="Online" />
        <x-ui::avatar name="Carlos André" color="blue" circle badge badge:color="green" badge:label="Online" />
        <x-ui::avatar name="Fernando Pinto" color="amber" circle />
    </x-ui::avatar.group>
    <x-ui::avatar.group max="2" label="Reviewers of #214">
        <x-ui::avatar name="Alex Nogueira" color="ink" size="sm" circle />
        <x-ui::avatar icon="sparkles" color="ai" size="sm" circle aria-label="AI agent" />
        <x-ui::avatar name="Carlos André" color="blue" size="sm" circle />
        <x-ui::avatar name="Fernanda Paiva" color="red" size="sm" circle />
        <x-ui::avatar name="Diego Rocha" color="green" size="sm" circle />
    </x-ui::avatar.group>
    <x-ui::avatar.group max="3" size="xs" label="Compact, in tables">
        @foreach (['Ana Souza', 'Bruno Lima', 'Carla Dias', 'Diego Rocha', 'Elisa Prado', 'Fábio Reis', 'Gil Torres', 'Hana Lee'] as $person)
            <x-ui::avatar :name="$person" color="auto" size="xs" circle />
        @endforeach
    </x-ui::avatar.group>
</div>
```

### Workspaces, repositories and agent

Without circle the avatar is square: use it for entities. ink for workspace and repository, ai for the agent, outline to invite.

```blade
<x-ui::avatar name="DevAction" color="ink" />
<x-ui::avatar icon="github" color="ink" aria-label="sge-api repository" />
<x-ui::avatar name="Baptist Church" color="red" />
<x-ui::avatar icon="sparkles" color="ai" circle aria-label="AI agent" />
<x-ui::avatar icon="user-add-01" color="outline" circle as="button" aria-label="Invite someone" />
```

### In a person row

Avatar with name, email and role, like in the members list.

```blade
<div class="flex w-full max-w-md items-center gap-3 rounded-xl border border-line bg-card px-3 py-2.5">
    <x-ui::avatar name="Alex Nogueira" color="indigo" circle />
    <span class="flex flex-1 flex-col">
        <span class="font-medium">Alex Nogueira</span>
        <span class="text-[13px] text-muted">alex@devaction.com.br</span>
    </span>
    <x-ui::tag>Admin</x-ui::tag>
</div>
```

## API

Other HTML attributes (aria-label, wire:click, x-on:click) go straight to the root element.

### `x-ui::avatar`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | string | `null` | Generates the initials and the alt text. |
| `initials` | string | `null` | Custom initials; win over name. |
| `src` | string | `null` | Image URL. |
| `alt` | string | `name` | Image alt text. |
| `size` | xs \| sm \| base \| lg \| xl | `base` | 24, 32, 40, 56 and 72 px. |
| `color` | red … rose \| auto \| ink \| ai \| outline | `null` | Tailwind names mapped to tokens; auto uses a hash of the name. |
| `color:seed` | string | `name` | Stable seed for color="auto". |
| `circle` | boolean | `false` | Round for people; square for entities. |
| `icon` | string | `null` | Hugeicons icon instead of initials. |
| `tooltip` | string \| true | `null` | Wraps it in an x-ui::tooltip; true uses the name. |
| `tooltip:position` | top \| right \| bottom \| left | `top` | Tooltip side. |
| `badge` | true \| string \| slot | `null` | Status dot, counter or custom content. |
| `badge:color` | same colors as color | `null` | green online, amber away, red busy, no color offline. |
| `badge:position` | top left \| top right \| bottom left \| bottom right | `bottom right` | Badge corner. |
| `badge:circle` | boolean | `false` | Round counter. |
| `badge:variant` | solid \| outline | `solid` | Counter with background or border only. |
| `badge:label` | string | `null` | Status for screen readers. |
| `href` | string | `null` | Renders an <a>. |
| `as` | div \| button | `div` | Clickable avatar. |

### `x-ui::avatar.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `max` | int | `null` | Shows the first ones and collapses the rest into a +N avatar. |
| `size` | string | `sm` | Size of the +N and the overlap: xs · sm · base · lg. |
| `label` | string | `null` | Text next to the group. |
| `class` | string | `—` | Change the ring with *:ring-{color} for another background. |

## Accessibility

- With name, the initials are hidden and the full name is read by screen readers.
- The status dot with badge:label becomes role="img" with the status spelled out.
- An icon-only avatar needs an aria-label.
- A clickable avatar becomes an <a> or <button>, with visible focus.
