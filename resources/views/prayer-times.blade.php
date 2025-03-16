<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prayer Times - MusafirBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>
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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 