<x-layouts.main>
    <x-location-history-chart
        :labels="$location->historyTimestamped()->keys()"
        :title="$location->name"
        {{-- labels="['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '00:00']" --}}
        :available="$location->historyTimestamped()->pluck('available')"
        :out_of_order="$location->historyTimestamped()->pluck('out_of_order')"
        :inoperative="$location->historyTimestamped()->pluck('inoperative')"
        :planned="$location->historyTimestamped()->pluck('planned')"
        :unknown="$location->historyTimestamped()->pluck('unknown')"
        :blocked="$location->historyTimestamped()->pluck('blocked')"
        :chartType="request()->get('chartType')"
        >
    </x-location-history-chart>
</x-layouts.main>
