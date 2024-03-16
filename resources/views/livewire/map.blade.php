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
            @foreach ($publicLocations as $location)
                L.circle([{{ $location->address->lat }}, {{ $location->address->lng }}], {

                    color: 'blue',

                    fillColor: '#03f',

                    fillOpacity: 0.5,

                    radius: 50

                }).addTo(map).bindPopup('{{ $location->virtual_name }}');
            @endforeach

            @foreach ($locations as $location)

            L.circle([{{ $location->address->lat}}, {{ $location->address->lng }}], {

                color: 'red',

                fillColor: '#f03',

                fillOpacity: 0.5,

                radius: 50

            }).addTo(map).bindPopup('{{ $location->virtual_name }}');

            @endforeach
            @foreach ($otherNetworkLocations as $location)

            L.circle([{{ $location->address->lat }}, {{ $location->address->lng }}], {

                color: 'gray',

                fillColor: '#ccc',

                fillOpacity: 0.5,

                radius: 50

            }).addTo(map).bindPopup('{{ 'Roaming: ' . $location->virtual_name }}');

            @endforeach


        }
    }" class="h-[680px]" id="map"></div>
</div>
