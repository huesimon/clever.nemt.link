@props([
    'labels' => [],
    'datasets' => [],
    'title' => 'Chart',
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
                    legend: { display: false },
                    tooltip: {
                        displayColors: false,
                        callbacks: {
                            label(point) {
                                return point.parsed.y + ' ' + point.dataset.label
                            }
                        }
                    }
                }
            }
        })
    }
}"
class="w-full"
>
{{-- headline --}}
<div class="flex justify-between items-center">
    <h2 class="text-2xl font-bold">{{ $title }}</h2>
</div>
<canvas x-ref="canvas" class="rounded-lg bg-white p-8"></canvas>
</div>
