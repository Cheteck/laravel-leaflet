<div >
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

        .leaflet-marker-icon i {
            font-size: 20px; /* Ajustez la taille de l'icône ici */
        }

        /* Styles pour le mode sombre */
        body.dark-mode {
            background-color: #333;
            color: #fff;
        }

        .dark-mode #map {
            /* Styles spécifiques pour la carte en mode sombre si nécessaire */
        }

        .dark-mode-button {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000; /* Pour être au-dessus de la carte */
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            cursor: pointer;
        }

        .dark-mode .dark-mode-button {
            background-color: #343a40;
            color: #fff;
            border: 1px solid #495057;
        }
    </style>
    <link rel='stylesheet' href='https://unpkg.com/leaflet@1.8.0/dist/leaflet.css' crossorigin='' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <h1 class='text-center'>{{ $title ?? '' }}</h1>

    <div id='map'></div>

    <button class="dark-mode-button" onclick="toggleDarkMode()">
        <i class="fas fa-moon"></i> <span id="dark-mode-text">Mode Sombre</span>
    </button>


    <script src='https://unpkg.com/leaflet@1.8.0/dist/leaflet.js' crossorigin=''></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>
        let map, markers = [],
            polygons = [],
            ctlLayers,
            menuBase;

        /* ----------------------------- Initialiser la carte ----------------------------- */
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
            let icon = L.divIcon({
                className: 'custom-marker',
                html: `<i class="fas ${data.icon}" style="color: ${data.color ?? 'blue'}"></i>`,
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
            });

            return L.marker(data.position, {
                    draggable: data.draggable,
                    icon: icon
                })
                .on('click', (event) => markerClicked(event, index))
                .on('dragend', (event) => markerDragEnd(event, index));
        }

        /* --------------------------- Initialiser les marqueurs --------------------------- */
        function initMarkers(initialMarkers, options) {

            for (let index = 0; index < initialMarkers.length; index++) {

                const data = initialMarkers[index];
                const marker = generateMarker(data, index);
                marker.addTo(map).bindPopup(`<b>${data.title ?? ''}</b><br>${data.position.lat},  ${data.position.lng}`);
                map.panTo(data.position);
                markers.push(marker)
            }

            const geocoder = L.Control.Geocoder.nominatim();
            geocoder.reverse(
                {
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

        /* --------------------------- Initialiser les polygones --------------------------- */
        function initPolygons(initialPolygons) {

            for (let index = 0; index < initialPolygons.length; index++) {
                const data = initialPolygons[index];
                const polygon = L.polygon(data).addTo(map).bindPopup(`Je suis un Polygone`);
            }
        }

        /* --------------------------- Initialiser les polylignes --------------------------- */
        function initPolylines(initialPolylines) {

            for (let index = 0; index < initialPolylines.length; index++) {
                const data = initialPolylines[index];
                const polyline = L.polyline(data).addTo(map).bindPopup(`Je suis une Polyligne`);
            }
        }

        /* --------------------------- Initialiser les rectangles --------------------------- */
        function initRectangles(initialRectangles) {

            for (let index = 0; index < initialRectangles.length; index++) {
                const data = initialRectangles[index];
                const rectangle = L.rectangle(data).addTo(map).bindPopup(`Je suis un Rectangle`);
            }
        }

        /* --------------------------- Initialiser les cercles --------------------------- */
        function initCircles(initialCircles) {

            for (let index = 0; index < initialCircles.length; index++) {
                const data = initialCircles[index];
                const circle = L.circle(data.position, {
                    radius: data.radius
                }).addTo(map).bindPopup(`Je suis un Cercle`);
            }
        }

        /* ------------------------- Gérer l'événement de clic sur la carte ------------------------- */
        function mapClicked($event) {
            popup
                .setLatLng($event.latlng)
                .setContent(`Vous avez cliqué sur la carte à ${$event.latlng.toString()}`)
                .openOn(map);
            console.log($event.latlng.lat, $event.latlng.lng);
        }

        /* ------------------------ Gérer l'événement de clic sur le marqueur ----------------------- */
        function markerClicked($event, index) {
            console.log(map);
            console.log($event.latlng.lat, $event.latlng.lng);
        }

        /* ------------------------ Gérer l'événement de clic sur le polygone ----------------------- */
        function polygonClicked($event, index) {
            console.log(map);
            console.log($event.latlng.lat, $event.latlng.lng);
        }

        /* ----------------------- Gérer l'événement de fin de déplacement du marqueur ---------------------- */
        function markerDragEnd($event, index) {
            console.log(map);
            console.log($event.target.getLatLng());
        }

        /* ----------------------- Basculer le Mode Sombre ---------------------- */
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const darkModeText = document.getElementById('dark-mode-text');
            if (document.body.classList.contains('dark-mode')) {
                darkModeText.textContent = 'Mode Clair';
                console.log('Mode sombre activé');
            } else {
                darkModeText.textContent = 'Mode Sombre';
                console.log('Mode clair activé');
            }
        }

        // Vérifier la préférence de mode sombre au démarrage
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.body.classList.add('dark-mode');
            document.getElementById('dark-mode-text').textContent = 'Mode Clair';
        }
    </script>
</div>
