{{--
    Passos verticais: checklist de configuração, histórico de um processo.

    <x-ui::steps>
        <x-ui::steps.item state="done" label="Conta criada" description="agora" />
        <x-ui::steps.item state="current" label="Primeiro projeto" description="SGE · Campo Limpo" />
        <x-ui::steps.item label="Convidar o time" description="0 de 2" />
    </x-ui::steps>
--}}
<ol data-steps {{ $attributes->class(['flex flex-col']) }}>
    {{ $slot }}
</ol>
