<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prayer Times - MusafirBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        .prayer-times-card {
            max-width: 400px;
            margin: 0 auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .prayer-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .prayer-row:last-child {
            border-bottom: none;
        }
        .prayer-name {
            font-weight: 500;
            color: #333;
        }
        .prayer-time {
            font-weight: bold;
            color: #198754;
        }
        .current-prayer {
            background-color: #e8f5e9;
            border-left: 4px solid #198754;
        }
        .date-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            text-align: center;
        }
        body {
            transition: margin-left 0.3s ease-in-out;
        }
        .shifted {
            margin-left: 250px; /* Adjust this value based on navbar width */
        }

:root {
    --primary-color: #255F38;
    --secondary-color: #1F7D53;
    --white: #ffffff;
    --light-green: #e8f5e9;
}

/* Navbar styles */
.navbar-custom {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    padding: 1rem 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    min-height: 70px;
    z-index: 999;
}

.navbar-brand {
    color: var(--white) !important;
    font-size: 1.5rem;
    font-weight: 700;
    padding: 0.5rem 1rem;
    letter-spacing: 0.5px;
}

.navbar-brand i {
    color: var(--light-green);
    margin-right: 8px;
}

.nav-link {
    color: var(--white) !important;
    font-weight: 500;
    padding: 0.5rem 1rem !important;
    transition: all 0.3s ease;
    border-radius: 5px;
    margin: 0 0.2rem;
}

.nav-link:hover, .nav-link.active {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateY(-1px);
}

.navbar-toggler {
    border-color: var(--white);
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.9)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

/* Main content spacing */
.main-content {
    padding-top: 100px; /* Increased padding-top */
    padding-bottom: 2rem;
}

/* Search section styles */
.search-section {
    background-color: var(--white);
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

/* Add these new styles */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 998;  /* Below navbar (999) but above content */
}

.overlay.show {
    opacity: 1;
    visibility: visible;
}

@media (max-width: 991.98px) {
    .navbar-collapse {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        padding: 1rem;
        border-radius: 0 0 0.5rem 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
}
    </style>
</head>
<body>
    <!-- Add this div right after body tag -->
    <div class="overlay"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-compass"></i>
                MusafirBuddy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/prayer-times">Prayer Times</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Add margin-top to container to account for fixed navbar -->
    <div class="container py-5" style="margin-top: 70px;">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card prayer-times-card">
                    <div class="date-header">
                        <h4 class="mb-2">Prayer Times</h4>
                        <div id="location-info" class="text-muted small"></div>
                    </div>
                    <div id="prayer-times-container">
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary d-none" id="loading" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-danger d-none mt-3" id="error-message"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingSpinner = document.getElementById('loading');
            const errorMessage = document.getElementById('error-message');
            const prayerTimesContainer = document.getElementById('prayer-times-container');
            const locationInfo = document.getElementById('location-info');

            function showLoading() {
                loadingSpinner.classList.remove('d-none');
                errorMessage.classList.add('d-none');
            }

            function hideLoading() {
                loadingSpinner.classList.add('d-none');
            }

            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.classList.remove('d-none');
            }

            function formatPrayerTime(timestamp) {
                const date = new Date(timestamp * 1000);
                return date.toLocaleTimeString('en-MY', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }

            function createPrayerRow(name, time, isCurrentPrayer) {
                return `
                    <div class="prayer-row ${isCurrentPrayer ? 'current-prayer' : ''}">
                        <span class="prayer-name">${name}</span>
                        <span class="prayer-time">${formatPrayerTime(time)}</span>
                    </div>
                `;
            }

            function fetchPrayerTimes(latitude, longitude) {
                showLoading();
                fetch(`/api/v1/prayer-times?latitude=${latitude}&longitude=${longitude}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const prayerData = data.data;
                            const todayPrayers = prayerData.prayers[0];

                            if (todayPrayers) {
                                const prayers = [
                                    { name: 'Fajr', time: parseInt(todayPrayers.fajr) },
                                    { name: 'Syuruk', time: parseInt(todayPrayers.syuruk) },
                                    { name: 'Dhuhr', time: parseInt(todayPrayers.dhuhr) },
                                    { name: 'Asr', time: parseInt(todayPrayers.asr) },
                                    { name: 'Maghrib', time: parseInt(todayPrayers.maghrib) },
                                    { name: 'Isha', time: parseInt(todayPrayers.isha) }
                                ];

                                let html = '';
                                prayers.forEach((prayer) => {
                                    html += createPrayerRow(prayer.name, prayer.time, false);
                                });

                                prayerTimesContainer.innerHTML = html;

                                const dateStr = `${prayerData.month} ${todayPrayers.day}, ${prayerData.year}`;
                                const hijriDate = todayPrayers.hijri;
                                locationInfo.innerHTML = `
                                    <div>${dateStr}</div>
                                    <div>${hijriDate}</div>
                                    <div class="text-muted small mt-1">Zone: ${prayerData.zone}</div>
                                `;
                            } else {
                                showError('No prayer times available');
                            }
                        } else {
                            showError(data.message || 'Failed to fetch prayer times');
                        }
                    })
                    .catch(error => {
                        showError('Error fetching prayer times: ' + error.message);
                    })
                    .finally(() => {
                        hideLoading();
                    });
            }

            // Automatically request location when page loads
            if (navigator.geolocation) {
                showLoading();
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const { latitude, longitude } = position.coords;
                        fetchPrayerTimes(latitude, longitude);
                    },
                    (error) => {
                        showError('Error getting location: ' + error.message);
                    }
                );
            } else {
                showError('Geolocation is not supported by this browser');
            }
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            const overlay = document.querySelector('.overlay');

            navbarToggler.addEventListener('click', function() {
                overlay.classList.toggle('show');
            });

            // Close navbar when clicking overlay
            overlay.addEventListener('click', function() {
                navbarCollapse.classList.remove('show');
                overlay.classList.remove('show');
            });

            // Handle Bootstrap collapse events
            navbarCollapse.addEventListener('hidden.bs.collapse', function () {
                overlay.classList.remove('show');
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>