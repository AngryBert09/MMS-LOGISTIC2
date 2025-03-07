<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 500px;
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Route Result</h1>
        <div id="map"></div>
        <a href="{{ route('route.form') }}" class="btn btn-secondary mt-3">Back</a>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Extract the route coordinates and start/end points from the PHP variables
        const routeData = @json($routeData);
        const startLat = @json($startLat);
        const startLng = @json($startLng);
        const endLat = @json($endLat);
        const endLng = @json($endLng);

        // Check if the route data is valid
        if (routeData && routeData.features && routeData.features.length > 0) {
            const coordinates = routeData.features[0].geometry.coordinates;

            // Initialize the map
            const map = L.map('map').setView([startLat, startLng], 13);

            // Add a tile layer (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Convert coordinates to LatLng format for Leaflet
            const latLngs = coordinates.map(coord => L.latLng(coord[1], coord[0]));

            // Draw the route on the map
            const polyline = L.polyline(latLngs, {
                color: 'blue'
            }).addTo(map);

            // Fit the map to the route bounds
            map.fitBounds(polyline.getBounds());

            // Add markers for the start and end points
            L.marker([startLat, startLng]).addTo(map)
                .bindPopup('Start Location')
                .openPopup();

            L.marker([endLat, endLng]).addTo(map)
                .bindPopup('End Location');
        } else {
            // Handle invalid or missing route data
            document.getElementById('map').innerHTML = '<p class="text-danger">No route data found.</p>';
        }
    </script>
</body>

</html>
