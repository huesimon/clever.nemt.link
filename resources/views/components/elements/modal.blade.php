@props([
    'title',
])

<div x-data="{ open: false }" class="-ml-px flex w-0 flex-1" >
    <!-- Trigger -->
    <span x-on:click="open = true"
    class="inline-flex flex-1"
    >
        {{ $button }}
    </span>

    <!-- Modal -->
    <div
        x-show="open"
        style="display: none"
        x-on:keydown.escape.prevent.stop="open = false"
        role="dialog"
        aria-modal="true"
        x-id="['modal-title']"
        :aria-labelledby="$id('modal-title')"
        class="fixed inset-0 z-10 overflow-y-auto"
    >
        <!-- Overlay -->
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50"></div>

        <!-- Panel -->
        <div
            x-show="open" x-transition
            x-on:click="open = false"
            class="relative flex min-h-screen items-center justify-center p-4"
        >
            <div
                x-on:click.stop
                x-trap.noscroll.inert="open"
                class="relative w-full max-w-2xl overflow-y-auto rounded-xl bg-white p-12 shadow-lg"
            >
                <!-- Title -->
                <h2 class="text-3xl font-bold" :id="$id('modal-title')">{{ $title }}</h2>

                <!-- Content -->
                <div class="mt-4">
                    {{ $content }}
                </div>

                <!-- Buttons -->
                <div class="mt-8 flex space-x-2">
                    {{ $buttons }}
                </div>
            </div>
        </div>
    </div>
</div>
