<x-layouts.main>
    @php
        // This is done to prevent calling the function multiple times
        $timestamps = $location->historyTimestamped(now()->subDays(request()->get('days')));
    @endphp
    <x-location-history-chart
        :labels="$timestamps->keys()"
        :title="$location->name"
        {{-- labels="['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '00:00']" --}}
        :available="$timestamps->pluck('available')"
        :out_of_order="$timestamps->pluck('out_of_order')"
        :inoperative="$timestamps->pluck('inoperative')"
        :planned="$timestamps->pluck('planned')"
        :unknown="$timestamps->pluck('unknown')"
        :blocked="$timestamps->pluck('blocked')"
        :chartType="request()->get('chartType')"
        >
    </x-location-history-chart>
</x-layouts.main>
