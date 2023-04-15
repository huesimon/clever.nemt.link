@props([
    'labels' => [],
    'values' => [],
    'chartType' => 'line',
])

{{-- incase of multiple charts on 1 page, once will prevent the js from being added multiple times --}}
@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
@endpush
@endonce
<div
x-data="{
    labels: {{ $labels }},
    values: {{ $values }},
    init() {
        let chart = new Chart(this.$refs.canvas.getContext('2d'), {
            type: '{{ $chartType }}',
            data: {
                labels: this.labels,
                datasets: [{
                    data: this.values,
                    backgroundColor: '#77C1D2',
                    borderColor: '#77C1D2',
                }],
            },
            options: {
                interaction: { intersect: false },
                scales: { y: { beginAtZero: true }},
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        displayColors: false,
                        callbacks: {
                            label(point) {
                                return point.raw
                            }
                        }
                    }
                }
            }
        })

        this.$watch('values', () => {
            chart.data.labels = this.labels
            chart.data.datasets[0].data = this.values
            chart.update()
        })
    }
}"
class="w-full"
>
<canvas x-ref="canvas" class="rounded-lg bg-white p-8"></canvas>
</div>
