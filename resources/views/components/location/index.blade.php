<div class="" style="">
    <div class="bg-gray-100 py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($locations as $location)
                    <x-location.card :location="$location"/>
                @endforeach
            </ul>
        </div>
    </div>
</div>
