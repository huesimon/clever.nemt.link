<x-layouts.main>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
    @endpush
    <x-chart
        :labels="$location->historyTimestamped()->keys()"
        {{-- labels="['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '00:00']" --}}
        :values="$location->historyTimestamped()->pluck('available')"
        :chartType="request()->get('chartType')"
        >
    </x-chart>
</x-layouts.main>
