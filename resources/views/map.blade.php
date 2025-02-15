<!DOCTYPE html>
<html>

<head>
    <title>HERE Maps Search</title>
    <script src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
    <script src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
    <script src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
    <script src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
    <style>
        #map {
            width: 100%;
            height: 500px;
        }

        #search-bar {
            margin: 10px;
            padding: 10px;
            width: 300px;
        }
    </style>
</head>

<body>
    <input type="text" id="search-bar" placeholder="Search for a place...">
    <button onclick="searchPlace()">Search</button>
    <div id="map"></div>

    <script>
        const platform = new H.service.Platform({
            apikey: 'hTc68CXmvPkFt5qzdylo1l_gSeUl8d_ImWPaB2IkUW0' // Replace with your HERE API Key
        });
        const defaultLayers = platform.createDefaultLayers();
        const map = new H.Map(
            document.getElementById('map'),
            defaultLayers.vector.normal.map, {
                center: {
                    lat: 14.5995,
                    lng: 120.9842
                }, // Default location (Manila)
                zoom: 12
            }
        );
        const behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));
        const ui = H.ui.UI.createDefault(map, defaultLayers);

        function searchPlace() {
            const query = document.getElementById('search-bar').value;
            const service = platform.getSearchService();
            service.geocode({
                q: query
            }, (result) => {
                if (result.items.length > 0) {
                    const location = result.items[0].position;
                    map.setCenter({
                        lat: location.lat,
                        lng: location.lng
                    });
                    map.setZoom(15);

                    const marker = new H.map.Marker({
                        lat: location.lat,
                        lng: location.lng
                    });
                    map.addObject(marker);
                } else {
                    alert('Location not found');
                }
            }, alert);
        }
    </script>
</body>

</html>
