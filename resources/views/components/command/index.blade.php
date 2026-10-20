{{--
    Paleta de comandos (API no estilo Flux). Filtra as opções conforme se digita; ↑↓ navega e ↵ escolhe.

    <x-ui::modal.trigger name="comandos" shortcut="cmd.k">
        <x-ui::button icon="search-01">Buscar <x-ui::kbd>⌘K</x-ui::kbd></x-ui::button>
    </x-ui::modal.trigger>

    <x-ui::modal name="comandos" variant="bare">
        <x-ui::command>
            <x-ui::command.input placeholder="Buscar ou digitar um comando…" />
            <x-ui::command.items>
                <x-ui::command.group heading="Ações">
                    <x-ui::command.item icon="git-pull-request" description="sge-api #214" kbd="↵">Abrir review do PR</x-ui::command.item>
                </x-ui::command.group>
                <x-ui::command.empty>Nada encontrado.</x-ui::command.empty>
            </x-ui::command.items>
        </x-ui::command>
    </x-ui::modal>
--}}
<div
    data-command
    x-data="uiCommand"
    {{ $attributes->class(['flex w-[560px] max-w-full flex-col overflow-hidden rounded-2xl border border-line bg-card text-left text-ink shadow-[0_24px_60px_rgb(24_24_27/0.16)]']) }}
>
    {{ $slot }}
</div>
