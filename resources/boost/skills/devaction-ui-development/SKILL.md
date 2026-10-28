---
name: devaction-ui-development
description: Build Laravel and Livewire interfaces with the DevAction UI kit (devaction/ui) — x-ui:: Blade components for forms, tables, overlays, navigation, data, AI and full page layouts. Use whenever creating or editing Blade views, Livewire components, pages, layouts, forms, tables, modals, toasts or icons in an app that has devaction/ui installed, or when the user mentions DevAction UI or x-ui:: components.
---

# DevAction UI Development

DevAction UI is a kit of anonymous Blade components (`<x-ui::*>`) for Laravel 12/13 and Livewire 4, styled with Tailwind CSS v4 tokens, powered by Alpine (bundled with Livewire) and Hugeicons Stroke Rounded icons. Its API mirrors Flux, but it is its own package: never install, import or reference Flux.

## When to use this skill

- Building or editing any Blade view, Livewire component, page or layout in this app.
- Adding forms (inputs, selects, date pickers, uploads), tables, modals, drawers, toasts, menus, charts, kanban boards, AI chat UIs or auth/pricing pages.
- Choosing which component fits a requirement — read the component reference linked below before writing markup.

## Installation (already done if `devaction/ui` is in composer.json)

```bash
composer require devaction/ui
```

```css
/* resources/css/app.css */
@import 'tailwindcss';
@import '../../vendor/devaction/ui/resources/css/devaction-ui.css';
```

```js
// resources/js/app.js
import '../../vendor/devaction/ui/resources/js/devaction-ui.js';
```

Put `<x-ui::toast.group />` once in the main layout. Optional: `composer require livewire/blaze` compiles the components for speed.

## Golden rules

1. **Reuse before you write.** Every common UI piece already exists as `x-ui::…`. Check the index below; open the component's reference for props, slots and examples.
2. **Flux-style API.** Props for options (`variant`, `size`, `icon`, `label`, `description`), named slots for regions (`<x-slot:actions>`, `<x-slot:footer>`, `<x-slot:empty>`), sub-components with dots (`x-ui::table.row`, `x-ui::select.option`).
3. **`wire:model` goes on the component root.** Fields, selects, comboboxes, pillboxes, switches, sliders, date pickers, segmented controls, tabs, modals (open state) and tables (selection) all expose their value through `x-modelable`, so `wire:model`/`x-model` on the component just works. Validation errors for that property appear automatically.
4. **Tokens, not colors.** Use the kit's tokens: `bg-card`, `bg-panel`, `bg-wrap`, `bg-subtle`, `text-ink`, `text-ink-2`, `text-ink-3`, `text-muted`, `border-line`, `border-line-soft`, `bg-ai`, `text-accent`, status pairs `bg-ok-bg text-ok-fg`, `bg-warn-bg text-warn-fg`, `bg-risk-bg text-risk-fg`, `bg-info-bg text-info-fg`. Never add `dark:` variants — the `.dark` class on `<html>` switches every token.
5. **Icons:** `<x-ui::icon name="calendar-03" class="size-4 text-muted" />` (Hugeicons Stroke Rounded names, kebab-case). If an icon is missing, run `php artisan ui:icon calendar-add-01` instead of drawing SVG.
6. **Accessibility is built in** — keep it: give icon-only buttons an `aria-label`, keep `label` on fields, don't remove focus rings, never convey status by color alone (tags and badges always carry text).
7. **Translations:** kit texts follow the app locale (English default, Portuguese with `pt_BR`). Don't hard-code replacements in views; override a key in `lang/{locale}.json`.

## Livewire patterns

See the Livewire guide in the Guides index below for complete examples. Quick ones:

```php
use DevAction\Ui\Livewire\InteractsWithModals;
use DevAction\Ui\Livewire\InteractsWithToasts;
use DevAction\Ui\Ui;

use InteractsWithModals, InteractsWithToasts;

$this->modal('edit-deal')->show();          // or ->close()
$this->toast('Deal saved.', variant: 'success');
Ui::modal('confirm')->show();                // from any class inside a Livewire request
Ui::modals()->close();
```

- **Search options on the server:** `:filter="false"` plus the `input` slot (`x-ui::combobox.input`, `x-ui::select.input`, `x-ui::pillbox.input`) bound with `wire:model.live.debounce.250ms`. Always keep the selected options in the result set.
- **Tables:** `<x-ui::table :paginate="$this->orders">` — inside a Livewire component pagination uses `setPage()` automatically; sortable columns use `sortable :sorted="…" :direction="…" wire:click="sort('…')"`.
- **Drag and drop:** `x-ui::kanban.column sort="moveDeal"` (wire:sort with `(key, position, column)`), `x-ui::sortable-list sort="reorder"` (`(key, position)`).
- **Actions without a submit button:** `x-ui::otp-input submit="verify"`, `x-ui::notification-center mark-all="markAllAsRead"`, `x-ui::diff comment="addComment"`.

## Layouts

Six page shells share the same pieces. See the Layouts reference in the Layouts index below.

- `x-ui::layout.classic` — sidebar + inset panel with header (add `collapsed` for the icon rail; ⌘B toggles).
- `x-ui::layout.topbar` — horizontal navigation, wide content.
- `x-ui::layout.split` — navigation, list and opened item.
- `x-ui::layout.centered` — narrow column for settings, onboarding, checkout.
- `x-ui::layout.docs` — sections, article and "On this page".

## Theming

Tokens, dark mode and density are explained in the Theming guide in the Guides index below.

## Common pitfalls

- Don't put Blade directives (`@if`, `@js`) inside a component tag's attribute list — use bound attributes: `:wire:click="$canResolve ? 'resolve' : null"` (a `null` value omits the attribute) or `data-*` attributes read by Alpine.
- Props of a component can't call functions in their defaults; compute in an `@php` block.
- When `@aware` and `@props` declare the same key and the parent passes it explicitly, keep the key in `@props` only.
- Floating panels (dropdowns, calendars, combobox lists, tooltips) already escape `overflow-hidden` parents via the `x-ui-float` directive — don't add `overflow-visible` hacks.
- Use `x-ui::select` (native) for short lists; `variant="listbox"` when you need search, multiple selection, icons or server search.
- Tests: assert on stable hooks (`data-*` attributes, `wire:model`, `aria-*`) rather than on translated copy, or set the locale explicitly.

## Components

Each link opens the full reference: description, Blade examples, Livewire code, props table and accessibility notes.

### Base

- [Accordion](references/accordion.md) — Opens and closes sections of content. For FAQs, contract milestones and grouped lists.
- [Alert](references/alert.md) — Reports the result of something that just happened. Every alert has an icon and a title; the icon defaults per variant.
- [Avatar](references/avatar.md) — Represents people, workspaces, repositories, clients and the AI agent. Initials come from the name and the color is stable by hash.
- [Badge](references/badge.md) — Status in running text, with an optional dot. Never color alone: always with text.
- [Banner](references/banner.md) — A notice strip that applies to the whole page: news, instability, billing or a lost connection.
- [Button](references/button.md) — Triggers an action or navigates. Variants, sizes, loading state and an icon-only version.
- [Callout](references/callout.md) — Highlights important information and guides to the next action. Amber always means "the AI did it", red means "someone is missing".
- [Code block](references/code-block.md) — Shows code with a header, syntax colors, copy, line numbers and line highlighting. The panel is dark in both themes.
- [Description list](references/description-list.md) — Label and value pairs for detail pages: client, contract, repository, owner.
- [Empty state](references/empty-state.md) — Every empty state says what the area is, why it is empty and what the next step is, with a single primary button.
- [Kbd](references/kbd.md) — Shows a keyboard shortcut in Geist Mono.
- [Panel](references/panel.md) — The container for the design's panels: a 6 px frame and a card with heading, supporting line, actions and footer. Underneath, it is the Flux-style Card.
- [Progress](references/progress.md) — Shows how much of something is done: milestone scope, hour bank, billed installments or a background process.
- [Reactions](references/reactions.md) — Emoji reactions under a comment: one pill per emoji with its count, yours highlighted, and a button to add one.
- [Separator](references/separator.md) — A line that separates blocks: plain, with text in the middle or vertical between items on a row.
- [Skeleton](references/skeleton.md) — Holds the place of content while it loads, in the same shape. The shimmer stops with prefers-reduced-motion.
- [Spinner](references/spinner.md) — Shows that something is loading when you cannot tell how much is left. For known progress, use Progress.
- [Stat](references/stat.md) — A highlighted number with a label, change and context, for dashboards and summary strips.
- [Tabs](references/tabs.md) — Organizes content into panels in the same area, without leaving the page. Arrows, Home and End switch tabs.
- [Tag](references/tag.md) — A status label in uppercase Geist Mono, with border, background and text in the same tone.
- [Timeline](references/timeline.md) — Shows a sequence of events or steps: the history of a PR, the progress of a milestone, the stages of a process.

### Overlay

- [Command palette](references/command-palette.md) — ⌘K search with groups and shortcuts. Filters as you type; ↑↓ navigate, ↵ picks and Esc closes.
- [Confirm dialog](references/confirm-dialog.md) — Asks for a yes before an action with effects outside the app, like publishing or sending. Icon, title, what will happen and two ways out.
- [Context menu](references/context-menu.md) — Actions for a row or card via right-click (or Shift+F10), with the same items as the ⋯ menu.
- [Cookie consent](references/cookie-consent.md) — Cookie notice for privacy laws (GDPR, LGPD): accept all, necessary only, or choose by category. The choice is remembered.
- [Danger dialog](references/danger-dialog.md) — For things that can’t be undone. The action only unlocks after the person types the resource name, as the design requires.
- [Drawer](references/drawer.md) — Side panel to edit without leaving the screen: profile, task, contract item. For short, interrupting decisions, use Modal.
- [Dropdown](references/dropdown.md) — Action menu that opens from a button. Arrow keys, Home, End and the first letter navigate; Esc closes and returns to the button.
- [Hover card](references/hover-card.md) — Card that opens on hover or focus: person, PR, company. Shows context without taking people off the page.
- [Modal](references/modal.md) — For interrupting decisions: confirm, a short form, a notice. Focus is trapped, Esc closes and the page does not scroll behind it.
- [Notification center](references/notification-center.md) — Notification center: tabs with unread counts, grouped by day, mark one or all as read. Standalone or in the bell.
- [Popover](references/popover.md) — Panel that opens from a button for free-form content: milestone details, filters, a short form. For lists of actions, use Dropdown.
- [Progress toast](references/progress-toast.md) — A toast for long background tasks: the background fills as it advances and, when complete, shows a check and goes away.
- [Toast group](references/toast-group.md) — Short notices after an action. They stack: collapsed, the stack shows the front one with two peeking behind; on hover or focus it expands into a list and the timers pause.
- [Tooltip](references/tooltip.md) — Names icon-only buttons and shows the shortcut when there is one. Appears on hover after 400 ms and immediately on keyboard focus; Esc closes it.

### Form

- [Autocomplete](references/autocomplete.md) — Free text with suggestions as you type. Unlike the combobox, the value can be any text; suggestions only help to complete it.
- [Choice](references/choice.md) — Checkbox, radio and choice cards built on native inputs. They work in plain forms and with wire:model on the input or the group.
- [Color picker](references/color-picker.md) — Picks a color by dragging, from a preset or by typing the hex. Always outputs hex, ready to save.
- [Combobox](references/combobox.md) — Select with search: a single choice from a long list, filtering as you type. Accents and capitals don’t get in the way of the filter.
- [Copy field](references/copy-field.md) — A value ready to copy: API key, webhook secret, invite link. Secrets show masked.
- [Date picker](references/date-picker.md) — Pick a date or a range from a calendar, with shortcuts and limits. The field shows the date in words and how far away it is.
- [Date range](references/date-range.md) — Date range with two months side by side, shortcuts and an optional time. The choice only counts after Apply.
- [Dropzone](references/dropzone.md) — Area to drag files in or click to choose, with the upload queue below. It uses the native file input, so Livewire uploads work without configuration.
- [Editor](references/editor.md) — Rich text for delivery notes, descriptions and comments. Formatting toolbar, keyboard shortcuts and a "/" command menu. No dependencies.
- [Filter menu](references/filter-menu.md) — Grouped filters with submenus. The menu stays open while you check options and only filters on Apply.
- [Input group](references/input-group.md) — Text fields with label, icon, help and error, attached add-ons (prefix, suffix, select) and the native select. The base of every form in the kit.
- [Money input](references/money-input.md) — Money amount: formats the currency while you type and sends the raw number to Livewire and to the form.
- [Number input](references/number-input.md) — Number with − and +, limits, step and unit: milestone hours, installments, discount.
- [OTP input](references/otp-input.md) — Verification code by email or SMS: one box per digit, pasting fills them all and phones suggest the code.
- [Pillbox](references/pillbox.md) — Several values as pills inside a field: pick from a list (with local or server search) or type free text, like tags and emails.
- [Segmented](references/segmented.md) — A single choice among a few options that switch the view instantly: mode, layout, period, theme.
- [Select](references/select.md) — Pick from a list. Native by default; with variant="listbox" it gains search, multiple options and server-side search, like Flux.
- [Signature field](references/signature-field.md) — A signature drawn with a finger or mouse, or typed. It outputs SVG, light enough to store next to the document.
- [Slider](references/slider.md) — Pick a value or a range by dragging: minimum coverage, review strictness, contract value.
- [Switch](references/switch.md) — Turns something on and off that applies immediately, without a save button. For choices that only apply after saving, use a checkbox.
- [Textarea](references/textarea.md) — Long text: milestone scope, notes, comments. With a counter, an actions footer and auto-grow.
- [Timer](references/timer.md) — Tracks time on a task for the hour bank. When stopped, it reports the seconds so you can log the hours.

### Navigation

- [Breadcrumb](references/breadcrumb.md) — Shows where the page sits and leads back to the levels above. The last item is the current page.
- [Page header](references/page-header.md) — Top of an app page: breadcrumb, status, heading with a context line, actions and tabs, always in the same order.
- [Pagination](references/pagination.md) — Moves between the pages of a list. Takes the Laravel paginator and works with regular links or with Livewire's WithPagination.
- [Resizable](references/resizable.md) — Two panels with a divider that changes their size: diff and AI summary, list and detail.
- [Sidebar](references/sidebar.md) — The app main navigation: 236 px open, 64 px collapsed with icons only and, on mobile, a top bar with the menu in a drawer.
- [Stepper](references/stepper.md) — Shows which step of a flow the person is on: done, current, failed and to do.
- [Steps](references/steps.md) — Steps in a column joined by a line: a setup checklist or the history of a process.

### Data

- [Activity](references/activity.md) — A single conversation for a task: comments, events and the AI review on one rail, with the comment box at the end.
- [Calendar](references/calendar.md) — Event calendar by month or week: deliveries, due dates, meetings. Rendered on the server; navigation works with links or with Livewire.
- [Chart](references/chart.md) — Line, column, bar, donut and sparkline charts as SVG rendered on the server. No JavaScript library; the hover uses Alpine.
- [Checklist](references/checklist.md) — A list of steps to tick off, with a progress bar and the option to hide what is already done.
- [Data table](references/data-table.md) — Data table in a real <table>: sorting, bulk selection, expandable rows, groups, pagination, states, sticky columns and cards on mobile.
- [Diff](references/diff.md) — A file diff with conversations pinned to lines: comment, reply, suggest a change and resolve, like in a PR review.
- [File tree](references/file-tree.md) — Tree of the files changed in a PR: folders built from the paths, status, +/− and AI findings per file.
- [Kanban](references/kanban.md) — Board of columns with cards: tasks, pipeline deals, candidates. Dragging between columns uses Livewire 4's wire:sort.
- [PDF viewer](references/pdf-viewer.md) — A document to review and sign: thumbnails, zoom, pages and marked signature fields, with the signers panel.
- [Properties](references/properties.md) — The details column next to a task, card or contract: small sections with label and value rows.
- [Sortable list](references/sortable-list.md) — A list that reorders by dragging the handle, with the keyboard or with buttons, and announces changes to screen readers.

### AI

- [AI prompt](references/ai-prompt.md) — Input for chatting with the AI: grows up to 8 lines, accepts attachments, / commands and @ mentions, and turns into a stop button while the AI responds.
- [Aura](references/aura.md) — A light border that spins around a card, toast or button while the AI works. The glow version pulses without spinning, for things that need you.
- [Chat bubble](references/chat-bubble.md) — Messages in a conversation with the AI or another person: sender, time, status, typing, copy and rate the answer.

### Primitives

- [Field](references/field.md) — Lays out a form field: label, control, supporting text and error, with the label, description and error helpers.
- [Icon](references/icon.md) — Hugeicons Stroke Rounded icons as inline SVG that follow the text color and the theme.
- [Typography](references/typography.md) — Heading, text and eyebrow: titles, supporting text and the uppercase mono section label.

### Templates

- [Auth](references/auth.md) — Ready-made authentication screens: login, registration, magic link and password reset, in a centered or split layout. They work with POST and a CSRF token or with Livewire.
- [Pricing](references/pricing.md) — A pricing page: cards with price, features and an action, plus a monthly / yearly billing toggle.

### Layouts

- [Layouts](references/layouts.md) — Six page shells (classic, rail, topbar, split, centered, docs) plus `main`, `logo` and `spacer`.

### Guides

- [Livewire](references/livewire.md) — Modals, toasts, server-side search, tables, drag and drop and actions in Livewire 4 components.
- [Theming](references/theming.md) — Tokens, dark mode, density and per-app overrides.
