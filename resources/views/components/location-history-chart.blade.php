@props([
'labels' => [],
'available' => null,
'available_ccs' => null,
'available_chademo' => null,
'available_type2' => null,
'out_of_order' => null,
'out_of_order_ccs' => null,
'out_of_order_chademo' => null,
'out_of_order_type2' => null,
'inoperative' => null,
'inoperative_ccs' => null,
'inoperative_chademo' => null,
'inoperative_type2' => null,
'planned' => null,
'planned_ccs' => null,
'planned_chademo' => null,
'planned_type2' => null,
'unknown' => null,
'unknown_ccs' => null,
'unknown_chademo' => null,
'unknown_type2' => null,
'blocked' => null,
'blocked_ccs' => null,
'blocked_chademo' => null,
'blocked_type2' => null,
'title' => 'Location History Chart',
])

<x-chart :labels="$labels" :title="$title" :datasets="[
        [
            'data' => $available,
            'backgroundColor' => '#77C1D2',
            'borderColor' => '#77C1D2',
            'label' => 'Available'
        ],
        [
            'data' => $availableCcs,
            'backgroundColor' => '#3fa6be',
            'borderColor' => '#3fa6be',
            'label' => 'Available CCs',
            'hidden' => $availableCcs->sum() ? false : true
        ],
        [
            'data' => $availableChademo,
            'backgroundColor' => '#51b0c5',
            'borderColor' => '#51b0c5',
            'label' => 'Available Chademo',
            'hidden' => $availableChademo->sum() ? false : true
        ],
        [
            'data' => $availableType2,
            'backgroundColor' => '#64b8cc',
            'borderColor' => '#64b8cc',
            'label' => 'Available Type2',
            'hidden' => $availableType2->sum() ? false : true
        ],
        [
            'data' => $out_of_order,
            'backgroundColor' => '#F2C94C',
            'borderColor' => '#F2C94C',
            'label' => 'Out of order',
            'hidden' => $out_of_order->sum() ? false : true
        ],
        [
            'data' => $out_of_order_ccs,
            'backgroundColor' => '#e1ae10',
            'borderColor' => '#e1ae10',
            'label' => 'Out of order CCs',
            'hidden' => $out_of_order_ccs->sum() ? false : true
        ],
        [
            'data' => $out_of_order_chademo,
            'backgroundColor' => '#efbb1c',
            'borderColor' => '#efbb1c',
            'label' => 'Out of order Chademo',
            'hidden' => $out_of_order_chademo->sum() ? false : true
        ],
        [
            'data' => $out_of_order_type2,
            'backgroundColor' => '#f0c234',
            'borderColor' => '#f0c234',
            'label' => 'Out of order Type2',
            'hidden' => $out_of_order_type2->sum() ? false : true
        ],
        [
            'data' => $inoperative,
            'backgroundColor' => '#F2994A',
            'borderColor' => '#F2994A',
            'label' => 'Inoperative',
            'hidden' => $inoperative->sum() ? false : true
        ],
        [
            'data' => $inoperative_ccs,
            'backgroundColor' => '#df7210',
            'borderColor' => '#df7210',
            'label' => 'Inoperative CCs',
            'hidden' => $inoperative_ccs->sum() ? false : true
        ],
        [
            'data' => $inoperative_chademo,
            'backgroundColor' => '#ef7e1a',
            'borderColor' => '#ef7e1a',
            'label' => 'Inoperative Chademo',
            'hidden' => $inoperative_chademo->sum() ? false : true
        ],
        [
            'data' => $inoperative_type2,
            'backgroundColor' => '#f08c32',
            'borderColor' => '#f08c32',
            'label' => 'Inoperative Type 2',
            'hidden' => $inoperative_type2->sum() ? false : true
        ],
        [
            'data' => $planned,
            'backgroundColor' => '#F2994A',
            'borderColor' => '#F2994A',
            'label' => 'Planned',
            'hidden' => $planned->sum() ? false : true
        ],
        // [
        //     'data' => $planned_ccs,
        //     'backgroundColor' => '#F2994A',
        //     'borderColor' => '#F2994A',
        //     'label' => 'Planned CCs',
        //     'hidden' => $planned_ccs->sum() ? false : true
        // ],
        // [
        //     'data' => $planned_chademo,
        //     'backgroundColor' => '#F2994A',
        //     'borderColor' => '#F2994A',
        //     'label' => 'Planned Chademo',
        //     'hidden' => $planned_chademo->sum() ? false : true
        // ],
        // [
        //     'data' => $planned_type2,
        //     'backgroundColor' => '#F2994A',
        //     'borderColor' => '#F2994A',
        //     'label' => 'Planned Type2',
        //     'hidden' => $planned_type2->sum() ? false : true
        // ],
        [
            'data' => $unknown,
            'backgroundColor' => '#EB5757',
            'borderColor' => '#EB5757',
            'label' => 'Unknown',
            'hidden' => $unknown->sum() ? false : true
        ],
        [
            'data' => $unknown_ccs,
            'backgroundColor' => '#EB5757',
            'borderColor' => '#EB5757',
            'label' => 'Unknown CCs',
            'hidden' => $unknown_ccs->sum() ? false : true,
        ],
        [
            'data' => $unknown_chademo,
            'backgroundColor' => '#EB5757',
            'borderColor' => '#EB5757',
            'label' => 'Unknown Chademo',
            'hidden' => $unknown_chademo->sum() ? false : true,
        ],
        [
            'data' => $unknown_type2,
            'backgroundColor' => '#EB5757',
            'borderColor' => '#EB5757',
            'label' => 'Unknown Type2',
            'hidden' => $unknown_type2->sum() ? false : true,
        ],
        [
            'data' => $blocked,
            'backgroundColor' => '#2F80ED',
            'borderColor' => '#2F80ED',
            'label' => 'Blocked',
            'hidden' => $blocked->sum() ? false : true
        ],
        // [
        //     'data' => $blocked_ccs,
        //     'backgroundColor' => '#2F80ED',
        //     'borderColor' => '#2F80ED',
        //     'label' => 'Blocked CCs',
        //     'hidden' => $blocked_ccs->sum() ? false : true
        // ],
        // [
        //     'data' => $blocked_chademo,
        //     'backgroundColor' => '#2F80ED',
        //     'borderColor' => '#2F80ED',
        //     'label' => 'Blocked Chademo',
        //     'hidden' => $blocked_chademo->sum() ? false : true
        // ],
        // [
        //     'data' => $blocked_type2,
        //     'backgroundColor' => '#2F80ED',
        //     'borderColor' => '#2F80ED',
        //     'label' => 'Blocked Type2',
        //     'hidden' => $blocked_type2->sum() ? false : true
        // ]
    ]" :showLegend="true">
</x-chart>

</div>
