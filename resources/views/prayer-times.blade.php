<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prayer Times - MusafirBuddy</title>
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
        .prayer-times-card {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 100px;
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
    </style>
</head>
<body>

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">MusafirBuddy</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{url('/')}}" >Home</a></li>
          <li> <a href="{{url('/Place')}}">Find Location</a></li>
          <li class="dropdown"><a href="#"><span>Prayer</span><i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="{{url('/prayer-times')}}">Prayer Times</a></li>
              <li><a href="{{url('/prayer-guide')}}">Prayer Guide</a></li>
            </ul>
          </li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>
  <main class="main">
    <div class="container py-5">
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
    </script>

   
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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