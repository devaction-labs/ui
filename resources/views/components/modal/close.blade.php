{{--
    Fecha o modal em que está ao clicar no conteúdo.

    <x-ui::modal.close>
        <x-ui::button>Cancelar</x-ui::button>
    </x-ui::modal.close>
--}}
<div
    data-modal-close
    x-on:click="$dispatch('ui-modal-close-self')"
    {{ $attributes->class(['contents']) }}
>
    {{ $slot }}
</div>
