<?php

namespace DevAction\Ui\Mcp;

use DevAction\Ui\Support\ComponentManifest;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Monta o markup <x-ui::…> de um componente a partir do manifesto, validando as chaves. Sem dependência de MCP.
 */
class MarkupBuilder
{
    private const PASSTHROUGH = '/^(wire:|x-|@|aria-|data-)/';

    private const ATTRIBUTE_NAME = '/^(?:[A-Za-z:][A-Za-z0-9:._-]*|@[a-z][a-z0-9.-]*)\z/';

    private const SLOT_NAME = '/^[a-z][a-z0-9]*(-[a-z0-9]+)*\z/';

    private const GLOBAL_ATTRIBUTES = ['class', 'id', 'style', 'name', 'value', 'placeholder', 'title', 'href', 'type'];

    public function __construct(private readonly ComponentManifest $manifest = new ComponentManifest) {}

    /**
     * @param  array<string, string|int|float|bool>  $props
     * @param  array<string, string>  $slots
     *
     * @throws InvalidArgumentException componente ou chave desconhecidos
     */
    public function build(string $name, array $props = [], ?string $slot = null, array $slots = []): string
    {
        $component = $this->manifest->find($name);

        if ($component === null) {
            throw new InvalidArgumentException(sprintf('Unknown component "%s".', $name));
        }

        $tag = 'x-ui::'.$component['name'];
        $valid = $this->validKeys($component);
        $attributes = '';

        foreach ($props as $key => $value) {
            $key = (string) $key;

            if (preg_match(self::ATTRIBUTE_NAME, $key) !== 1) {
                throw new InvalidArgumentException(sprintf('Invalid attribute name "%s".', $key));
            }

            $attribute = match (true) {
                str_starts_with($key, '@') => 'x-on:'.substr($key, 1),
                str_contains($key, ':') => $key,
                default => Str::kebab($key),
            };

            if (! in_array($attribute, $valid, true) && ! $this->passthrough($attribute)) {
                throw new InvalidArgumentException(sprintf('Unknown prop "%s" for %s. Valid: %s', $key, $tag, implode(', ', $valid)));
            }

            $attributes .= ' '.$this->attribute($attribute, $value);
        }

        $slot = $slot === null || $slot === '' ? null : $slot;

        if ($slot === null && $slots === []) {
            return '<'.$tag.$attributes.' />';
        }

        if ($slots === []) {
            return '<'.$tag.$attributes.'>'.$slot.'</'.$tag.'>';
        }

        $lines = [];

        foreach ($slots as $slotName => $content) {
            if (preg_match(self::SLOT_NAME, (string) $slotName) !== 1) {
                throw new InvalidArgumentException(sprintf('Invalid slot name "%s".', $slotName));
            }

            $lines[] = $this->indent(sprintf('<x-slot:%1$s>%2$s</x-slot:%1$s>', $slotName, $content));
        }

        if ($slot !== null) {
            $lines[] = $this->indent($slot);
        }

        return '<'.$tag.$attributes.">\n".implode("\n", $lines)."\n</".$tag.'>';
    }

    /**
     * @param  array<string, mixed>  $component
     * @return list<string>
     */
    private function validKeys(array $component): array
    {
        $keys = [];

        foreach ($component['props'] ?? [] as $prop) {
            $keys[] = $prop['attribute'] ?? $prop['name'];
            $keys[] = $prop['name'];
        }

        foreach ($component['attributes'] ?? [] as $attribute) {
            $keys[] = $attribute;
        }

        return array_values(array_unique(array_filter($keys, 'is_string')));
    }

    private function passthrough(string $attribute): bool
    {
        return preg_match(self::PASSTHROUGH, $attribute) === 1 || in_array($attribute, self::GLOBAL_ATTRIBUTES, true);
    }

    private function attribute(string $key, string|int|float|bool $value): string
    {
        if ($value === true) {
            return $key;
        }

        if ($value === false) {
            return sprintf(':%s="false"', $key);
        }

        if (is_int($value) || is_float($value)) {
            return sprintf(':%s="%s"', $key, $value);
        }

        if (str_starts_with($value, '$')) {
            if (str_contains($value, '"')) {
                throw new InvalidArgumentException(sprintf('Bound expression for "%s" cannot contain double quotes.', $key));
            }

            return sprintf(':%s="%s"', $key, $value);
        }

        return sprintf('%s="%s"', $key, str_replace(['{', '@'], ['&#123;', '&#64;'], e($value)));
    }

    private function indent(string $text): string
    {
        return preg_replace('/^(?=.)/m', '    ', $text) ?? $text;
    }
}
