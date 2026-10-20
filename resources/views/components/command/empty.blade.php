{{--
    Aparece quando nenhuma opção bate com a busca.

    <x-ui::command.empty>Nada encontrado.</x-ui::command.empty>
--}}
<div
    x-show="! hasResults()"
    x-cloak
    role="status"
    {{ $attributes->class(['flex flex-col items-center gap-1.5 px-4 py-8 text-center text-sm text-muted']) }}
>
    <x-ui::icon name="search-01" class="size-5" />
    {{ $slot }}
</div>
