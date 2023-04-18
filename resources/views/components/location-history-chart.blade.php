@props([
    'labels' => [],
    'available' => null,
    'out_of_order' => null,
    'inooperative' => null,
    'planned' => null,
    'unknown' => null,
    'blocked' => null,
    'chartType' => 'line',
])

<x-chart
    :labels="$labels"
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
            'inoperative' => $inooperative,
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
    ></x-chart>

</div>
