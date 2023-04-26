@props([
    'labels' => [],
    'available' => null,
    'out_of_order' => null,
    'inoperative' => null,
    'planned' => null,
    'unknown' => null,
    'blocked' => null,
    'title' => 'Location History Chart',
])

<x-chart
    :labels="$labels"
    :title="$title"
    :datasets="[
        [
            'data' => $available,
            'backgroundColor' => '#77C1D2',
            'borderColor' => '#77C1D2',
            'label' => 'Available'
        ],[
            'data' => $out_of_order,
            'backgroundColor' => '#F2C94C',
            'borderColor' => '#F2C94C',
            'label' => 'Out of order'
        ],[
            'data' => $inoperative,
            'backgroundColor' => '#F2994A',
            'borderColor' => '#F2994A',
            'label' => 'Inoperative'
        ],[
            'data' => $planned,
            'backgroundColor' => '#F2994A',
            'borderColor' => '#F2994A',
            'label' => 'Planned'
        ],[
            'data' => $unknown,
            'backgroundColor' => '#EB5757',
            'borderColor' => '#EB5757',
            'label' => 'Unknown'
        ],[
            'data' => $blocked,
            'backgroundColor' => '#2F80ED',
            'borderColor' => '#2F80ED',
            'label' => 'Blocked'
        ]
    ]"
    >

    <x-slot:extra class="flex flex-row space-x-2">
        <div
            x-data="{}"
            x-on:change="$refs.form.submit()">
            <form
                x-ref="form"
                class="flex flex-row justify-between items-center"
                action="{{ url()->current() }}" method="GET">
                <div class="flex flex-col">
                    <label for="days" class="block text-sm font-medium leading-6 text-gray-900">Days</label>
                    <select id="days" name="days" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option value="1" {{ request('days') == 1 ? 'selected' : '' }}>1 days</option>
                        <option value="2" {{ request('days') == 2 ? 'selected' : '' }}>2 days</option>
                        <option value="7" {{ request('days') == 7 ? 'selected' : '' }}>7 days</option>
                        <option value="14" {{ request('days') == 14 ? 'selected' : '' }}>14 days</option>
                    </select>
                </div>
            </form>
        </div>
    </x-slot:extra>
</x-chart>

</div>
