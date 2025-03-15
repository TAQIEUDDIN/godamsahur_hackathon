<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#ffffff">
    <title>Location Search</title>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #map { height: 500px; }
        .marker { width: 20px; height: 20px; border-radius: 50%; }
        .marker-restaurant { background-color: #FF5733; }
        .marker-mosque { background-color: #33FF57; }
        .marker-hotel { background-color: #3357FF; }
        .current-location {
            position: absolute;
            bottom: 20px;
            left: 10px;
            z-index: 1;
            background: white;
            padding: 5px 10px;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="input-group mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Please enter location">
                    <button class="btn btn-primary" onclick="searchLocation()">Search</button>
                </div>
            </div>
        </div>
        
        <!-- Add this new section after the search input -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card" id="distanceCard" style="display: none;">
                    <div class="card-body">
                        <h5 class="card-title">Distance Information</h5>
                        <p class="card-text" id="distanceInfo"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div id="map" class="mb-3">
                    <div class="current-location" id="currentLocation">
                        <small>Getting your location...</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div id="results"></div>
            </div>
        </div>
    </div>

    <script src='https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js'></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        mapboxgl.accessToken = '{{ config('mapbox.access_token') }}';
        let map;
        let markers = [];
        let userLocation = null;
        let userLocationMarker = null;
        let userLocationName = 'Your current location';

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radius of the earth in km
            const dLat = deg2rad(lat2 - lat1);
            const dLon = deg2rad(lon2 - lon1);
            const a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
                Math.sin(dLon/2) * Math.sin(dLon/2); 
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            const distance = R * c; // Distance in km
            return distance.toFixed(2);
        }

        function deg2rad(deg) {
            return deg * (Math.PI/180);
        }

        function getGoogleMapsUrl(lat, lng, name) {
            return `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}&destination_place_id=${encodeURIComponent(name)}`;
        }

        function getWazeUrl(lat, lng) {
            return `https://www.waze.com/ul?ll=${lat},${lng}&navigate=yes`;
        }

        // Initialize map
        document.addEventListener('DOMContentLoaded', () => {
            map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [101.6869, 3.1390], // Malaysia coordinates
                zoom: 10
            });

            // Get user's location
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(position => {
                    userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // Get location name from coordinates using reverse geocoding
                    fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${userLocation.lng},${userLocation.lat}.json?access_token=${mapboxgl.accessToken}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.features && data.features.length > 0) {
                                userLocationName = data.features[0].place_name;
                                document.getElementById('currentLocation').innerHTML = 
                                    `<small>Location: ${userLocationName} ✓</small>`;
                            }
                        })
                        .catch(error => console.error('Error getting location name:', error));

                    // Add marker for user's location
                    userLocationMarker = new mapboxgl.Marker({
                        color: '#4285F4'
                    })
                    .setLngLat([userLocation.lng, userLocation.lat])
                    .setPopup(new mapboxgl.Popup().setHTML('Your Location'))
                    .addTo(map);

                    // Update location display
                    document.getElementById('currentLocation').innerHTML = 
                        `<small>Your location found ✓</small>`;

                    // Center map on user's location
                    map.flyTo({
                        center: [userLocation.lng, userLocation.lat],
                        zoom: 12
                    });
                }, error => {
                    console.error('Error getting location:', error);
                    document.getElementById('currentLocation').innerHTML = 
                        `<small class="text-danger">Could not get your location</small>`;
                });
            }
        });

        function calculateMarhalah(distance){
            if (distance==81){
                marhalah="Your distance is excatly 2 marhalah, your can jama' and qasar your prayer";
            }else if (distance>81 ){
                marhalah="Your distance is more than 2 marhalah, your can jama' and qasar your prayer";
            }
            else{
                marhalah="Your distance is less than 2 marhalah, your can't jama' and qasar your prayer";
            }
            return marhalah;
        }

        function searchLocation() {
            const query = document.getElementById('searchInput').value;
            if (!query) return;

            // Clear previous markers
            markers.forEach(marker => marker.remove());
            markers = [];

            // Show loading state
            document.getElementById('results').innerHTML = 
                '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';

            // Debug log
            console.log('Searching for:', query);

            fetch(`/v1/mapbox-search?query=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Unknown error occurred');
                }

                // Calculate and display distance if we have both locations
                if (data.location && data.location.center && userLocation) {
                    const [destLng, destLat] = data.location.center;
                    const distance = calculateDistance(
                        userLocation.lat,
                        userLocation.lng,
                        destLat,
                        destLng
                    );
                


                    // Show the distance card with actual location names
                    document.getElementById('distanceCard').style.display = 'block';
                    document.getElementById('distanceInfo').innerHTML = `
                        <strong>From:</strong> ${userLocationName}<br>
                        <strong>To:</strong> ${query}<br>
                        <strong>Distance:</strong> ${distance} kilometers<br> 
                        <strong>Marhalah:</strong> ${calculateMarhalah(distance)}<br>
                        <div class="mt-3">
                            <a href="${getGoogleMapsUrl(destLat, destLng, query)}" 
                               class="btn btn-primary me-2" 
                               target="_blank">
                                <i class="bi bi-google"></i> Navigate with Google Maps
                            </a>
                            <a href="${getWazeUrl(destLat, destLng)}" 
                               class="btn btn-info" 
                               target="_blank">
                                <i class="bi bi-cursor"></i> Navigate with Waze
                            </a>
                        </div>
                    `;

                }

                // Center map on main location
                if (data.location && data.location.center) {
                    const [lng, lat] = data.location.center;
                    map.flyTo({ center: [lng, lat], zoom: 13 });
                }

                displayResults(data.places);
            })
            .catch(error => {
                console.error('Search error:', error);
                document.getElementById('results').innerHTML = 
                    `<div class="alert alert-danger">
                        <p>Error: ${error.message}</p>
                        <small>Please try again or check your connection</small>
                    </div>`;
                document.getElementById('distanceCard').style.display = 'none';
            });
        }

        function displayResults(places) {
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = '';

            console.log('Displaying results:', places); // Add this debug log

            // Create tabs for different place types
            const placeTypes = Object.keys(places);
            
            let totalPlaces = 0;
            let hasContent = false;
            
            placeTypes.forEach(type => {
                // Skip if no places of this type or empty array
                if (!places[type] || places[type].length === 0) {
                    return;
                }
                
                totalPlaces += places[type].length;
                hasContent = true;

                const typeSection = document.createElement('div');
                typeSection.className = 'mb-4';
                typeSection.innerHTML = `
                    <h5 class="mb-3">${type.charAt(0).toUpperCase() + type.slice(1)}s (${places[type].length})</h5>
                    <div class="list-group">
                        ${places[type].map(place => {
                            let distance = userLocation ? 
                                calculateDistance(
                                    userLocation.lat,
                                    userLocation.lng,
                                    place.center[1],
                                    place.center[0]
                                ) : null;

                            return `
                                <div class="list-group-item">
                                    <h6 class="mb-1">${place.text || place.name || 'Unnamed Place'}</h6>
                                    <small>${place.place_name || place.properties?.address || ''}</small>
                                    ${distance ? `<div class="mt-1"><small class="text-primary">Distance: ${distance} km</small></div>` : ''}
                                    ${place.properties?.categories ? 
                                        `<div><small class="text-muted">${place.properties.categories.join(', ')}</small></div>` : ''}
                                    
                                    <!-- Add navigation buttons -->
                                    <div class="mt-2">
                                        <a href="${getGoogleMapsUrl(place.center[1], place.center[0], place.text)}" 
                                           class="btn btn-sm btn-outline-primary me-2" 
                                           target="_blank">
                                            <i class="bi bi-google"></i> Google Maps
                                        </a>
                                        <a href="${getWazeUrl(place.center[1], place.center[0])}" 
                                           class="btn btn-sm btn-outline-info" 
                                           target="_blank">
                                            <i class="bi bi-cursor"></i> Waze
                                        </a>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                `;
                resultsDiv.appendChild(typeSection);

                // Add markers for this place type
                places[type].forEach(place => {
                    if (!place.center) {
                        console.warn('Place missing center coordinates:', place);
                        return;
                    }
                    
                    const marker = new mapboxgl.Marker({
                        color: getMarkerColor(type)
                    })
                    .setLngLat(place.center)
                    .setPopup(new mapboxgl.Popup().setHTML(`
                        <strong>${place.text || 'Location'}</strong><br>
                        ${place.place_name || place.properties?.address || ''}
                    `))
                    .addTo(map);
                    
                    markers.push(marker);
                });
            });

            if (!hasContent) {
                resultsDiv.innerHTML = '<div class="alert alert-warning">No places found in this location. Try a different search term.</div>';
            } else {
                const summaryDiv = document.createElement('div');
                summaryDiv.className = 'alert alert-success mb-3';
                summaryDiv.innerHTML = `Found ${totalPlaces} places in ${document.getElementById('searchInput').value}`;
                resultsDiv.insertBefore(summaryDiv, resultsDiv.firstChild);
            }
        }

        function getMarkerColor(type) {
            const colors = {
                restaurant: '#FF5733',
                mosque: '#33FF57',
                hotel: '#3357FF'
            };
            return colors[type] || '#000000';
        }


    </script>
</body>
</html>