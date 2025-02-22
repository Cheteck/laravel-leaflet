<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        .text-center {
            text-align: center;
        }

        #map {
            width: '100%';
            height: 600px;
        }
    </style>
    <link rel='stylesheet' href='https://unpkg.com/leaflet@1.8.0/dist/leaflet.css' crossorigin='' />
</head>

<body>
    <h1 class='text-center'>Laravel Leaflet Maps - It Works</h1>
    <div id='map'></div>

    <div class="map-controls dark:bg-gray-800 p-4 rounded-lg shadow-lg">
        <div class="flex gap-2 mb-4">
            <input type="text" id="address-search" placeholder="Rechercher une adresse..."
                class="dark:bg-gray-700 dark:text-white flex-grow px-4 py-2 rounded-lg">
            <button onclick="searchAddress()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div id="route-controls" class="hidden">
            <button onclick="calculateRoute()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-route mr-2"></i>Calculer l'itinéraire
            </button>
        </div>
    </div>

    <script src='https://unpkg.com/leaflet@1.8.0/dist/leaflet.js' crossorigin=''></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>
        let map, markers = [],
            polygons = [],
            ctlLayers, menuBase;
        let routeMarkers = [];
        /* ----------------------------- Initialize Map ----------------------------- */
        function initMap() {

            const options = {!! json_encode($options) !!};
            const initialMarkers = {!! json_encode($initialMarkers ?? '') !!};
            const initialPolygons = {!! json_encode($initialPolygons ?? '') !!};
            const initialPolylines = {!! json_encode($initialPolylines ?? '') !!};
            const initialRectangles = {!! json_encode($initialRectangles ?? '') !!};
            const initialCircles = {!! json_encode($initialCircles ?? '') !!};

            map = L.map('map', {
                center: {
                    lat: (options.center) ? options.center.lat : -23.347509137997484,
                    lng: (options.center) ? options.center.lng : -47.84753617004771
                },
                zoom: options.zoom || 18,
                zoomControl: options.zoomControl || true,
                minZoom: options.minZoom || 13,
                maxZoom: options.maxZoom || 18,
            });

            var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            });
            if (options.googleview || true) {

                var imagens = L.tileLayer('http://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                    attribution: '© Google Maps'
                });
                var menuBase = {
                    "Google Maps": imagens,
                    "OpenStreetMap": osm
                };
                map.addLayer(imagens);
                var ctlLayers = L.control.layers(menuBase).addTo(map);
            }

            map.addLayer(osm);

            map.on('click', mapClicked);
            if (initialMarkers != '') {
                initMarkers(initialMarkers, options);
            }
            if (initialPolygons != '') {
                initPolygons(initialPolygons);
            }
            if (initialPolylines != '') {
                initPolylines(initialPolylines);
            }
            if (initialRectangles != '') {
                initRectangles(initialRectangles);
            }
            if (initialCircles != '') {
                initCircles(initialCircles);
            }
        }
        initMap();

        const popup = L.popup();

        function generateMarker(data, index) {
            return L.marker(data.position, {
                    draggable: data.draggable
                })
                .on('click', (event) => markerClicked(event, index))
                .on('dragend', (event) => markerDragEnd(event, index));
        }


        /* --------------------------- Initialize Markers --------------------------- */
        function initMarkers(initialMarkers, options) {

            for (let index = 0; index < initialMarkers.length; index++) {

                const data = initialMarkers[index];
                const marker = generateMarker(data, index);
                marker.addTo(map).bindPopup(`<b>${data.position.lat},  ${data.position.lng}</b>`);
                map.panTo(data.position);
                markers.push(marker)
            }

            const geocoder = L.Control.Geocoder.nominatim();
            geocoder.reverse({
                    lat: (options.center) ? options.center.lat : -23.347509137997484,
                    lng: (options.center) ? options.center.lng : -47.84753617004771
                },
                map.getZoom(),
                (results) => {
                    if (results.length) {
                        console.log("formatted_address", results[0].name)
                    }
                }
            );
        }

        /* --------------------------- Initialize Polygons --------------------------- */
        function initPolygons(initialPolygons) {

            for (let index = 0; index < initialPolygons.length; index++) {
                const data = initialPolygons[index];
                const polygon = L.polygon(data).addTo(map).bindPopup(`I am a Polygon`);
            }
        }

        /* --------------------------- Initialize Polylines --------------------------- */
        function initPolylines(initialPolylines) {

            for (let index = 0; index < initialPolylines.length; index++) {
                const data = initialPolylines[index];
                const polyline = L.polyline(data).addTo(map).bindPopup(`I am a Polyline`);
            }
        }

        /* --------------------------- Initialize Rectangles --------------------------- */
        function initRectangles(initialRectangles) {

            for (let index = 0; index < initialRectangles.length; index++) {
                const data = initialRectangles[index];
                const rectangle = L.rectangle(data).addTo(map).bindPopup(`I am a Rectangle`);
            }
        }

        /* --------------------------- Initialize Circles --------------------------- */
        function initCircles(initialCircles) {

            for (let index = 0; index < initialCircles.length; index++) {
                const data = initialCircles[index];
                const circle = L.circle(data.position, {
                    radius: data.radius
                }).addTo(map).bindPopup(`I am a Circle`);
            }
        }

        /* ------------------------- Handle Map Click Event ------------------------- */
        function mapClicked($event) {
            popup
                .setLatLng($event.latlng)
                .setContent(`You clicked the map at ${$event.latlng.toString()}`)
                .openOn(map);
            console.log($event.latlng.lat, $event.latlng.lng);
        }

        /* ------------------------ Handle Marker Click Event ----------------------- */
        function markerClicked($event, index) {
            console.log(map);
            console.log($event.latlng.lat, $event.latlng.lng);
        }

        /* ------------------------ Handle Polygon Click Event ----------------------- */
        function polygonClicked($event, index) {
            console.log(map);
            console.log($event.latlng.lat, $event.latlng.lng);
        }

        /* ----------------------- Handle Marker DragEnd Event ---------------------- */
        function markerDragEnd($event, index) {
            console.log(map);
            console.log($event.target.getLatLng());
        }

        async function searchAddress() {
            const address = document.getElementById('address-search').value;
            const response = await fetch(`/geocode?address=${encodeURIComponent(address)}`);
            const data = await response.json();

            if (data.length > 0) {
                const result = data[0];
                const latlng = [parseFloat(result.lat), parseFloat(result.lon)];

                map.setView(latlng, 16);

                const marker = L.marker(latlng, {
                    draggable: true
                }).addTo(map);

                routeMarkers.push(marker);
                document.getElementById('route-controls').classList.remove('hidden');
            }
        }

        async function calculateRoute() {
            if (routeMarkers.length >= 2) {
                const start = routeMarkers[0].getLatLng();
                const end = routeMarkers[1].getLatLng();

                const response = await fetch(
                    `/route?start[lat]=${start.lat}&start[lng]=${start.lng}&end[lat]=${end.lat}&end[lng]=${end.lng}`
                    );
                const data = await response.json();

                if (data.routes && data.routes.length > 0) {
                    const route = data.routes[0];
                    L.geoJSON(route.geometry, {
                        style: {
                            color: '#3B82F6',
                            weight: 4
                        }
                    }).addTo(map);
                }
            }
        }
    </script>
</body>

</html>
