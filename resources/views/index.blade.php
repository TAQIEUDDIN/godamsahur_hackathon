<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Search</title>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #map { height: 500px; }
        .marker { width: 20px; height: 20px; border-radius: 50%; }
        .marker-restaurant { background-color: #FF5733; }
        .marker-mosque { background-color: #33FF57; }
        .marker-hotel { background-color: #3357FF; }
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

        <div class="row">
            <div class="col-md-8">
                <div id="map" class="mb-3"></div>
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

        // Initialize map
        document.addEventListener('DOMContentLoaded', () => {
            map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [101.6869, 3.1390], // Malaysia coordinates
                zoom: 10
            });
        });

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
            .then(response => {
                console.log('Response status:', response.status);
                return response.json().then(data => {
                    console.log('Response data:', data);
                    if (!response.ok) {
                        throw new Error(data.message || `HTTP error! status: ${response.status}`);
                    }
                    return data;
                });
            })
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Unknown error occurred');
                }
                
                if (!data.places || Object.keys(data.places).length === 0) {
                    document.getElementById('results').innerHTML = 
                        '<div class="alert alert-warning">No places found in this location</div>';
                    return;
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
                        ${places[type].map(place => `
                            <div class="list-group-item">
                                <h6 class="mb-1">${place.text || place.name || 'Unnamed Place'}</h6>
                                <small>${place.place_name || place.properties?.address || ''}</small>
                                ${place.properties?.categories ? 
                                    `<div><small class="text-muted">${place.properties.categories.join(', ')}</small></div>` : ''}
                            </div>
                        `).join('')}
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