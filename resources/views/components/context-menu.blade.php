{{--
    Menu de contexto: botão direito, Shift+F10 ou a tecla Menu sobre a área abrem um x-ui::menu no ponto do clique.
    Use os mesmos itens do menu ⋯ da linha, para quem não usa o botão direito.

    <x-ui::context-menu>
        <x-ui::table.row>…</x-ui::table.row>

        <x-ui::menu>
            <x-ui::menu.item icon="view" kbd="↵">Abrir</x-ui::menu.item>
            <x-ui::menu.separator />
            <x-ui::menu.item icon="delete-02" variant="danger">Excluir tarefa</x-ui::menu.item>
        </x-ui::menu>
    </x-ui::context-menu>

    Dispara context-menu-open com o elemento clicado em detail.target (útil para saber qual linha foi).
--}}
<div
    data-context-menu
    x-data="uiContextMenu"
    x-on:contextmenu="openAt($event)"
    x-on:keydown.shift.f10.prevent="openFromKeyboard()"
    x-on:keydown.context-menu.prevent="openFromKeyboard()"
    x-on:click="if (! $event.target.closest('[data-dropdown-panel]')) close()"
    x-on:click.outside="close()"
    x-on:keydown.escape="if (open) { $event.preventDefault(); $event.stopPropagation(); close(true) }"
    x-on:scroll.window="close()"
    {{ $attributes->class(['relative']) }}
>
    {{ $slot }}
</div>
