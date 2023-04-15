@props([
    'labels' => [],
    'values' => [],
    'chartType' => 'line',
])

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
