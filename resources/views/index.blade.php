<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#ffffff">
    <title>MusafirBuddy - Your Muslim Travel Companion</title>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
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

        :root {
            --primary-color: #255F38;
            --secondary-color: #1F7D53;
        }

        /* Search section styles */
        .search-section {
            background-color: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .input-group {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-control {
            border-radius: 8px 0 0 8px !important;
            border: 2px solid #e0e0e0;
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0 8px 8px 0 !important;
        }

        /* Add this new style */
        .main .container {
            margin-top: 100px; /* Adds space below fixed header */
            padding: 20px;
        }

        /* Navbar Styles */
        .navbar {
            padding: 0;
        }

        .navbar ul {
            margin: 0;
            padding: 0;
            display: flex;
            list-style: none;
            align-items: center;
        }

        .navbar a {
            display: flex;
            align-items: center;
            padding: 10px 0 10px 30px;
            font-size: 15px;
            font-weight: 500;
            color: white;
            white-space: nowrap;
            transition: 0.3s;
        }

        .navbar a:hover {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Mobile Navigation */
        .mobile-nav-toggle {
            color: #fff;
            font-size: 28px;
            cursor: pointer;
            display: none;
            line-height: 0;
            transition: 0.5s;
            position: fixed;
            right: 20px;
            top: 20px;
            z-index: 9998;
        }

        @media (max-width: 1280px) {
            .mobile-nav-toggle {
                display: block;
            }

            .navbar {
                position: fixed;
                top: 0;
                right: -100%;
                width: 100%;
                max-width: 400px;
                bottom: 0;
                transition: 0.3s;
                z-index: 9997;
            }

            .navbar ul {
                position: absolute;
                inset: 0;
                padding: 50px 0;
                margin: 0;
                background: rgba(37, 95, 56, 0.9);
                overflow-y: auto;
                transition: 0.3s;
                z-index: 9998;
                flex-direction: column;
            }

            .navbar a {
                padding: 10px 20px;
                font-size: 15px;
                text-align: center;
                width: 100%;
            }

            .navbar-mobile {
                right: 0;
            }

            body.mobile-nav-active {
                overflow: hidden;
            }
        }

        .filter-container {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .filter-btn {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .filter-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .filter-btn:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="index-page">

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">MusafirBuddy</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{url('/')}}">Home</a></li>
          <li> <a href="{{url('/Place')}}">Find Location</a></li>
          
          @auth
            <li><a href="{{ route('reviews.index') }}">Reviews</a></li>
        @endauth
          @guest
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
          @else
            <li class="dropdown">
              <a href="#"><span>{{ Auth::user()->name }}</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
              <ul>
                <li><a href="{{ route('profile.show') }}">Profile</a></li>
                <li>
                  <a href="{{ route('logout') }}"
                     onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
                    Logout
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                  </form>
                </li>
              </ul>
            </li>
          @endguest
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <!-- Main content -->
   <main class="main">
    <div class="container" style="margin-top: 100px;">
        <div class="row">
            <div class="col-12">
                <div class="input-group mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Please enter location">
                    <button class="btn btn-primary" onclick="searchLocation()"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </div>

        <!-- Add this right after your search input group -->
        <div class="row mb-3" id="filterSection" style="display: none;">
            <div class="col-12">
                <div class="filter-container">
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-outline-secondary filter-btn active" data-filter="all">
                            All
                        </button>
                        <button class="btn btn-outline-secondary filter-btn" data-filter="mosque">
                            <i class="bi bi-building"></i> Mosques
                        </button>
                        <button class="btn btn-outline-secondary filter-btn" data-filter="restaurant">
                            <i class="bi bi-cup-hot"></i> Restaurants
                        </button>
                        <button class="btn btn-outline-secondary filter-btn" data-filter="hotel">
                            <i class="bi bi-house-door"></i> Hotels
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distance Information Card -->
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

        <!-- Map and Results -->
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Keep your existing JavaScript for map functionality -->
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

            fetch(`/v1/mapbox-search?query=${encodeURIComponent(query)}&type=mosque`, {
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
                               class="btn btn-primary me-3" 
                               target="_blank">
                                <i class="bi bi-google"></i> Navigate with Google Maps
                            </a>
                            <a href="${getWazeUrl(destLat, destLng)}" 
                               class="btn btn-info mt-2" 
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

                // Filter mosque results to only include masjid and surau
                if (data.places && data.places.mosque) {
                    data.places.mosque = data.places.mosque.filter(place => {
                        const categories = place.properties.categories || [];
                        const name = (place.text || '').toLowerCase();
                        return categories.some(category => 
                            category.toLowerCase().includes('masjid') ||
                            category.toLowerCase().includes('surau') ||
                            category.toLowerCase().includes('mosque')
                        ) ||
                        name.includes('masjid') ||
                        name.includes('surau') ||
                        name.includes('mosque');
                    });
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

            // Show filter section when search is performed
            document.getElementById('filterSection').style.display = 'block';
            
            // Initialize filters
            initializeFilters();
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

        // Add this to your existing JavaScript
        function initializeFilters() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const resultsContainer = document.getElementById('results');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to clicked button
                    button.classList.add('active');
                    
                    const filterValue = button.getAttribute('data-filter');
                    filterResults(filterValue);
                });
            });
        }

        function filterResults(filterValue) {
            const resultItems = document.querySelectorAll('.list-group');
            
            resultItems.forEach(item => {
                if (filterValue === 'all') {
                    item.style.display = 'block';
                } else {
                    const sectionTitle = item.previousElementSibling.textContent.toLowerCase();
                    if (sectionTitle.includes(filterValue)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
        }
    </script>
    </main>
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/vendor/php-email-form/validate.js"></script>
  <script src="/assets/vendor/aos/aos.js"></script>
  <script src="/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="/assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
 
  <script src="/assets/js/main.js"></script>

  <script>
  function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
    }
  }

  // Clear hash on page load
  if (window.location.hash) {
    window.history.replaceState("", document.title, window.location.pathname);
  }
</script>
<footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="d-flex align-items-center">
            <span class="sitename">MusafirBuddy</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Muzaffar Heights,</p>
            <p>Ayer Keroh, Melaka</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+60 11-21219683</span></p>
            <p><strong>Email:</strong> <span>mtaqieuddin03@gmail.com</span></p>
          </div>
        </div>
      </div>
    </div>

  </footer>
</body>
</html>