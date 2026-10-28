{{-- Filter menu: grouped filters with submenus, counts, colors and a single-choice group — see references/filter-menu.md --}}
<div class="flex flex-col gap-4 rounded-lg border border-line bg-card p-4 text-ink">
    <div class="h-96 w-full">
        <x-ui::filter-menu :value="['area' => ['backend']]">
            <x-ui::filter-menu.group name="status" label="Status" icon="checkmark-circle-02">
                <x-ui::filter-menu.option value="open" count="5">Open</x-ui::filter-menu.option>
                <x-ui::filter-menu.option value="done" count="3">Done</x-ui::filter-menu.option>
            </x-ui::filter-menu.group>
            <x-ui::filter-menu.group name="area" label="Area" icon="folder-01">
                <x-ui::filter-menu.option value="backend" color="#2563EB">Backend</x-ui::filter-menu.option>
                <x-ui::filter-menu.option value="frontend" color="#7C3AED">Frontend</x-ui::filter-menu.option>
                <x-ui::filter-menu.option value="infra" color="#16A34A">Infra</x-ui::filter-menu.option>
            </x-ui::filter-menu.group>
            <x-ui::filter-menu.group name="owner" label="Owner" icon="user">
                <x-ui::filter-menu.option value="ana">Ana Souza</x-ui::filter-menu.option>
                <x-ui::filter-menu.option value="bruno">Bruno Lima</x-ui::filter-menu.option>
            </x-ui::filter-menu.group>
            {{-- Variation: single-choice group, where picking one option replaces the previous --}}
            <x-ui::filter-menu.group name="due" label="Due date" icon="calendar-03" single>
                <x-ui::filter-menu.option value="overdue">Overdue</x-ui::filter-menu.option>
                <x-ui::filter-menu.option value="week">This week</x-ui::filter-menu.option>
                <x-ui::filter-menu.option value="month">This month</x-ui::filter-menu.option>
            </x-ui::filter-menu.group>
        </x-ui::filter-menu>
    </div>
</div>
