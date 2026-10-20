<?php

namespace DevAction\Ui\Support;

/**
 * Classes de posicionamento dos painéis de um x-ui::dropdown (popover e menu).
 *
 * Os painéis usam as variáveis --dropdown-gap e --dropdown-offset definidas no dropdown.
 */
class DropdownPosition
{
    public static function classes(string $position, string $align): string
    {
        $isVertical = in_array($position, ['top', 'bottom'], true);

        $side = match ($position) {
            'top' => 'bottom-full mb-(--dropdown-gap)',
            'left' => 'right-full mr-(--dropdown-gap)',
            'right' => 'left-full ml-(--dropdown-gap)',
            default => 'top-full mt-(--dropdown-gap)',
        };

        $alignment = match (true) {
            $isVertical && $align === 'center' => 'left-1/2 -translate-x-1/2',
            $isVertical && $align === 'end' => 'right-(--dropdown-offset)',
            $isVertical => 'left-(--dropdown-offset)',
            $align === 'center' => 'top-1/2 -translate-y-1/2',
            $align === 'end' => 'bottom-(--dropdown-offset)',
            default => 'top-(--dropdown-offset)',
        };

        return $side.' '.$alignment;
    }
}
