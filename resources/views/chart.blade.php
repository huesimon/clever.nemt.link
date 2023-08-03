<x-layouts.main>
    @php
        // This is done to prevent calling the function multiple times
        $timestamps = $location->historyTimestamped();
    @endphp
    <x-location-history-chart
        :labels="$timestamps->keys()"
        :title="$location->name"
        {{-- labels="['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '00:00']" --}}
        :available="$timestamps->pluck('available')"
        :available_ccs="$timestamps->pluck('available_ccs')"
        :available_chademo="$timestamps->pluck('available_chademo')"
        :available_type2="$timestamps->pluck('available_type2')"
        :out_of_order="$timestamps->pluck('out_of_order')"
        :out_of_order_ccs="$timestamps->pluck('out_of_order_ccs')"
        :out_of_order_chademo="$timestamps->pluck('out_of_order_chademo')"
        :out_of_order_type2="$timestamps->pluck('out_of_order_type2')"
        :inoperative="$timestamps->pluck('inoperative')"
        :inoperative_ccs="$timestamps->pluck('inoperative_ccs')"
        :inoperative_chademo="$timestamps->pluck('inoperative_chademo')"
        :inoperative_type2="$timestamps->pluck('inoperative_type2')"
        :planned="$timestamps->pluck('planned')"
        :planned_ccs="$timestamps->pluck('planned_ccs')"
        :planned_chademo="$timestamps->pluck('planned_chademo')"
        :planned_type2="$timestamps->pluck('planned_type2')"
        :unknown="$timestamps->pluck('unknown')"
        :unknown_ccs="$timestamps->pluck('unknown_ccs')"
        :unknown_chademo="$timestamps->pluck('unknown_chademo')"
        :unknown_type2="$timestamps->pluck('unknown_type2')"
        :blocked="$timestamps->pluck('blocked')"
        :blocked_ccs="$timestamps->pluck('blocked_ccs')"
        :blocked_chademo="$timestamps->pluck('blocked_chademo')"
        :blocked_type2="$timestamps->pluck('blocked_type2')"
        :chartType="request()->get('chartType')"
        >
    </x-location-history-chart>
</x-layouts.main>
