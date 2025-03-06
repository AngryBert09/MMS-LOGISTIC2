<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ride Booking with Map & Street View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Full-screen background map */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        #map {
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        #location-info {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2);
            font-family: Arial, sans-serif;
            font-size: 14px;
            z-index: 100;
        }


        /* Floating dashboard container at the bottom center */
        .dashboard {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            width: 90%;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dashboard h1 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        /* Adjust search bar styling within dashboard */
        .search-bar {
            margin-bottom: 15px;
        }

        .street-view-btn {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            cursor: pointer;
            white-space: nowrap;
        }

        .street-view-btn:hover {
            background-color: #218838;
        }

        .ride-details {
            margin-top: 15px;
        }

        .ride-details p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <!-- Fullscreen Map -->
    <div id="map"></div>

    <div id="location-info">
        <p><strong>Origin:</strong> <span id="origin-text">Loading...</span></p>
        <p><strong>Stopover:</strong> <span id="stopover-text">None</span></p>
        <p><strong>Destination:</strong> <span id="destination-text">Loading...</span></p>
    </div>

    <!-- Google Maps JavaScript API -->
    <script
        src="https://maps.gomaps.pro/maps/api/js?key=AlzaSyQj0hGu6jyxFCibM7y_ViDRrKBgj3HLLst&libraries=places&callback=initMap"
        async defer></script>
    <script>
        let map, directionsService, directionsRenderer;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: 14.5995,
                    lng: 120.9842
                },
                zoom: 12,
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: map
            });

            // Get Laravel-passed PHP variables
            const origin = "{{ $origin }}";
            const destination = "{{ $destination }}";
            const stopover = "{{ $stopover ?? '' }}"; // Optional stopover

            // Update floating div with values
            document.getElementById('origin-text').textContent = origin;
            document.getElementById('destination-text').textContent = destination;
            if (stopover) {
                document.getElementById('stopover-text').textContent = stopover;
            }

            if (origin && destination) {
                calculateRoute(origin, destination, stopover);
            }
        }

        function calculateRoute(origin, destination, stopover) {
            let request = {
                origin: origin,
                destination: destination,
                travelMode: google.maps.TravelMode.DRIVING,
                waypoints: stopover ? [{
                    location: stopover,
                    stopover: true
                }] : []
            };

            directionsService.route(request, function(result, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                } else {
                    alert('Could not retrieve route: ' + status);
                }
            });
        }

        window.onload = initMap;
    </script>



    <!-- Google Maps API -->


</body>

</html>
