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
        ],[
            'data' => $out_of_order,
            'backgroundColor' => '#F2C94C',
            'borderColor' => '#F2C94C',
        ],[
            'inoperative' => $inooperative,
            'backgroundColor' => '#F2994A',
            'borderColor' => '#F2994A',
        ],[
            'data' => $planned,
            'backgroundColor' => '#F2994A',
            'borderColor' => '#F2994A',
        ],[
            'data' => $unknown,
            'backgroundColor' => '#EB5757',
            'borderColor' => '#EB5757',
        ],[
            'data' => $blocked,
            'backgroundColor' => '#2F80ED',
            'borderColor' => '#2F80ED',
        ]
    ]"
    ></x-chart>

</div>
