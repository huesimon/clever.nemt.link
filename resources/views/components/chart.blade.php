@props([
    'labels' => [],
    'datasets' => [],
    'title' => 'Chart',
    // 'chartType' => 'line',
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
            type: '{{ request()->get('chartType') ?? 'line' }}',
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
    <div class="flex space-x-2">
        <a href="{{ request()->fullUrlWithQuery(['chartType' => 'line']) }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l2-2m0 0l7-7 7 7m-7-7v18M5 21l7-7 7 7"></path>
            </svg>
        </a>
        <a href="{{ request()->fullUrlWithQuery(['chartType' => 'bar']) }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
        </a>
    </div>
</div>
<canvas x-ref="canvas" class="rounded-lg bg-white p-8"></canvas>
</div>
