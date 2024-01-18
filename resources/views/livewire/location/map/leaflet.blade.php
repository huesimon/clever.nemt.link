@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

<div
    x-data="
    map = L.map('map').setView([56.0394039, 11.5787184], 7);
    map.preferCanvas = true;
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);
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
    "

    class="h-[680px]"
    id="map">
</div>
