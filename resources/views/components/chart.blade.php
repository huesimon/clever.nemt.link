@props([
    'labels' => [],
    'datasets' => [],
    'title' => 'Chart',
    'chartType' => 'line',
    'showLegend' => false,
    'legendPosition' => 'bottom'
])
{{-- incase of multiple charts on 1 page, once will prevent the js from being added multiple times --}}
@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js" integrity="sha512-UXumZrZNiOwnTcZSHLOfcTs0aos2MzBWHXOHOuB0J/R44QB0dwY5JgfbvljXcklVf65Gc4El6RjZ+lnwd2az2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush
@endonce

<div
x-data="{
    init() {
        let chart = new Chart(this.$refs.canvas.getContext('2d'), {
            type: '{{ $chartType }}',
            data: {
                labels: {{ Js::from($labels) }},
                datasets: {{ Js::from($datasets) }},
            },
            options: {
                interaction: { intersect: false },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                },
                plugins: {
                    legend: {
                        display: {{ $showLegend ? 'true' : 'false' }},
                        'position': '{{ $legendPosition }}',
                    },
                    tooltip: {
                        displayColors: false,
                        callbacks: {
                            label(point) {
                                return point.parsed.y + ' ' + point.dataset.label
                            }
                        }
                    },
                    zoom: {
                        pan: {
                            enabled: true,
                            mode: 'x',
                            modifierKey: 'ctrl',
                        },
                        zoom: {
                            drag: {
                                enabled: true
                            },
                          mode: 'x',
                        }
                    }
                }
            }
        })
        this.$watch('values', () => {
            chart.data.labels = this.labels
            chart.data.datasets = this.datasets
            chart.update()
        })
    }
}"
class="w-full"
>

<div class="flex flex-col space-y-5">
    <div class="flex justify-between items-center mb-2">
        <h2 class="text-2xl font-bold">{{ $title }}</h2>
    </div>
</div>
<canvas x-ref="canvas" class="rounded-lg bg-white p-8"></canvas>
</div>
