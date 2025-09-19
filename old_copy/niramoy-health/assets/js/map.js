// ====== Map Functionality ======
document.addEventListener('DOMContentLoaded', function () {
  // Initialize map
  let map;
  let userMarker;
  let hospitalMarkers = [];

  function initMap() {
    // Default center (Dhaka, Bangladesh)
    const defaultCenter = [23.8103, 90.4125];

    // Create map
    map = L.map('hospital-map').setView(defaultCenter, 12);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Try to get user's location
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        position => {
          const userLocation = [position.coords.latitude, position.coords.longitude];

          // Add user marker
          userMarker = L.marker(userLocation, {
            icon: L.icon({
              iconUrl: 'assets/images/user-marker.png',
              iconSize: [32, 32],
              iconAnchor: [16, 32],
              popupAnchor: [0, -32]
            })
          }).addTo(map);

          userMarker.bindPopup('Your Location').openPopup();

          // Center map on user location
          map.setView(userLocation, 12);

          // Load nearest hospitals
          loadNearestHospitals(userLocation[0], userLocation[1]);
        },
        error => {
          console.error('Error getting user location:', error);
          // Load hospitals with default center
          loadNearestHospitals(defaultCenter[0], defaultCenter[1]);
        }
      );
    } else {
      console.error('Geolocation is not supported by this browser.');
      // Load hospitals with default center
      loadNearestHospitals(defaultCenter[0], defaultCenter[1]);
    }
  }

  // Load nearest hospitals
  function loadNearestHospitals(lat, lng) {
    const hospitalList = document.getElementById('hospital-list');

    // Show loading spinner
    hospitalList.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;

    // Fetch nearest hospitals from API
    fetch(`api/getNearestHospitals.php?lat=${lat}&lng=${lng}`)
      .then(response => response.json())
      .then(data => {
        if (data.success && data.hospitals.length > 0) {
          // Clear existing markers
          hospitalMarkers.forEach(marker => map.removeLayer(marker));
          hospitalMarkers = [];

          // Display hospitals
          displayHospitals(data.hospitals);
        } else {
          // No hospitals found
          hospitalList.innerHTML = `
                        <div class="no-hospitals">
                            <i class="fas fa-hospital-alt"></i>
                            <p>No hospitals found nearby</p>
                        </div>
                    `;
        }
      })
      .catch(error => {
        console.error('Error loading hospitals:', error);
        hospitalList.innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Error loading hospitals. Please try again later.</p>
                    </div>
                `;
      });
  }

  // Display hospitals on map and in list
  function displayHospitals(hospitals) {
    const hospitalList = document.getElementById('hospital-list');
    let listHTML = '';

    hospitals.forEach(hospital => {
      // Add marker to map
      const marker = L.marker([hospital.latitude, hospital.longitude], {
        icon: L.icon({
          iconUrl: 'assets/images/hospital-marker.png',
          iconSize: [32, 32],
          iconAnchor: [16, 32],
          popupAnchor: [0, -32]
        })
      }).addTo(map);

      // Add popup to marker
      marker.bindPopup(`
                <div class="hospital-popup">
                    <h4>${hospital.name}</h4>
                    <p><i class="fas fa-map-marker-alt"></i> ${hospital.address}, ${hospital.district}</p>
                    <p><i class="fas fa-phone"></i> ${hospital.phone}</p>
                    <div class="popup-actions">
                        <button class="btn btn-sm btn-primary" onclick="getDirections(${hospital.latitude}, ${hospital.longitude})">Get Directions</button>
                        <button class="btn btn-sm btn-success" onclick="callHospital('${hospital.phone}')">Call Now</button>
                    </div>
                </div>
            `);

      hospitalMarkers.push(marker);

      // Add to list
      listHTML += `
                <div class="hospital-item" data-lat="${hospital.latitude}" data-lng="${hospital.longitude}">
                    <h4>${hospital.name}</h4>
                    <p><i class="fas fa-map-marker-alt"></i> ${hospital.address}, ${hospital.district}</p>
                    <p><i class="fas fa-phone"></i> ${hospital.phone}</p>
                    <p class="hospital-distance"><i class="fas fa-route"></i> ${hospital.distance} km away</p>
                    <div class="hospital-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="getDirections(${hospital.latitude}, ${hospital.longitude})">Get Directions</button>
                        <button class="btn btn-sm btn-outline-success" onclick="callHospital('${hospital.phone}')">Call Now</button>
                    </div>
                </div>
            `;
    });

    hospitalList.innerHTML = listHTML;

    // Add click event to list items
    document.querySelectorAll('.hospital-item').forEach(item => {
      item.addEventListener('click', function () {
        const lat = parseFloat(this.getAttribute('data-lat'));
        const lng = parseFloat(this.getAttribute('data-lng'));
        map.setView([lat, lng], 15);

        // Open popup for the corresponding marker
        hospitalMarkers.forEach(marker => {
          const markerLatLng = marker.getLatLng();
          if (markerLatLng.lat === lat && markerLatLng.lng === lng) {
            marker.openPopup();
          }
        });
      });
    });
  }

  // Initialize map when page loads
  if (document.getElementById('hospital-map')) {
    initMap();
  }

  // Global functions for buttons
  window.getDirections = function (lat, lng) {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        position => {
          const userLat = position.coords.latitude;
          const userLng = position.coords.longitude;

          // Open Google Maps with directions
          window.open(`https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLng}&destination=${lat},${lng}`, '_blank');
        },
        error => {
          // Open Google Maps with destination only
          window.open(`https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`, '_blank');
        }
      );
    } else {
      // Open Google Maps with destination only
      window.open(`https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`, '_blank');
    }
  };

  window.callHospital = function (phone) {
    window.open(`tel:${phone}`, '_self');
  };
});