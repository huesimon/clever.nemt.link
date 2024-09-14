<div>
    <div x-data="{
        init() {
            let map = new L.map('map', {
                center: [56.0394039, 11.5787184],
                zoom: 7,
                renderer: L.canvas()
            });
            let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
            map.addLayer(layer);
            @foreach ($locations as $location)
                L.circle([{{ $location->address->lat }}, {{ $location->address->lng }}], {

                    color: '{{ $location->origin === App\Enums\Origin::Clever ?
                    $location->is_public_visible == 'Always' ? 'blue' : 'red'
                    : $location->origin->circleColor() }}',

                    fillColor: '{{ $location->origin === App\Enums\Origin::Clever ?
                    $location->is_public_visible == 'Always' ? 'blue' : 'red'
                    : $location->origin->circleColor() }}',

                    fillOpacity: 0.5,

                    radius: 50

                }).addTo(map).bindPopup('{{ $location->virtual_name }}');
            @endforeach
        }
    }" class="h-[680px]" id="map"></div>
    <div>
        <ul>
            @foreach (App\Enums\Origin::cases() as $item)
                <li class="mt-3 inline-block">
                    <a
                        href="{{ route('map', ['origin' => $item->value]) }}"
                        class="rounded-md bg-{{$item->circleColor()}}-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-{{$item->circleColor()}}-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-{{$item->circleColor()}}-400">
                        {{ $item->label() }}
                    </a>
                </li>
            @endforeach
        </ul>
      </div>
</div>
