# Code block · `<x-ui::code-block>`

Shows code with a header, syntax colors, copy, line numbers and line highlighting. The panel is dark in both themes.

## Examples

### With a file name

filename and language build the header; the copy button comes with it.

```blade
<x-ui::code-block filename="resources/css/app.css" language="css" class="w-full max-w-xl">
    @import "tailwindcss";
    @import "../../vendor/devaction/ui/resources/css/devaction-ui.css";

    @source "../../vendor/devaction/ui/resources/views";
</x-ui::code-block>
```

### Blade, numbers and highlight

For Blade code in the slot, wrap it in a verbatim block. lines shows numbering and highlight marks lines with the AI’s amber strip.

```blade
<x-ui::code-block filename="review.blade.php" language="blade" lines highlight="2-3" class="w-full max-w-xl">
    @verbatim
    <x-ui::callout variant="ai" icon="sparkles">
        <x-ui::callout.heading>The AI finished the review</x-ui::callout.heading>
        <x-ui::callout.text>2 issues and 3 suggestions on #142.</x-ui::callout.text>
    </x-ui::callout>
    @endverbatim
</x-ui::code-block>
```

### Without a header

Without filename or language, copy appears in the corner on hover or focus.

```blade
<x-ui::code-block language="bash" class="w-full max-w-xl">
    # install the kit
    composer require devaction/ui
    php artisan vendor:publish --tag=ui-icons
</x-ui::code-block>
```

### Other languages

PHP, JavaScript and JSON in the palette colors: tags in green, attributes and variables in blue, strings in pink.

```blade
<div class="grid w-full gap-3 lg:grid-cols-2">
    <x-ui::code-block filename="AppServiceProvider.php" language="php">
        public function boot(): void
        {
            Blaze::optimize()->in(resource_path('views/components'));
        }
    </x-ui::code-block>
    <x-ui::code-block filename="app.js" language="js">
        import '../../vendor/devaction/ui/resources/js/devaction-ui.js';

        // the kit's Alpine components
        const theme = localStorage.getItem('theme');
    </x-ui::code-block>
</div>
```

## API

Syntax highlighting is done on the server, with no dependencies, and covers what the kit’s docs use.

### `x-ui::code-block`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `code` | string | `slot` | Code text; an alternative to the slot. |
| `filename` | string | `null` | File name in the header. |
| `language` | blade \| html \| heex \| php \| js \| css \| bash \| json \| text | `null` | Syntax colors and header label. |
| `copyable` | boolean | `true` | Copy button. |
| `lines` | boolean | `false` | Line numbers. |
| `highlight` | string | `null` | Marked lines: "2" or "2,4-6". |
| `wrap` | boolean | `false` | Wraps long lines instead of scrolling. |
| `flush` | boolean | `false` | No rounded corners, to embed in another card. |

## Accessibility

- The copy button has an aria-label and announces that the code was copied (in the app locale).
- Line numbers stay out of the selection and the screen reader.
- Syntax color contrast above 4.5:1 on the dark background.
- Long lines scroll inside the block, with no horizontal page scroll.
