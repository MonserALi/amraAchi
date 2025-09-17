// Map functionality
document.addEventListener('DOMContentLoaded', function () {
  // Initialize map when page loads
  initializeMap();

  // Function to initialize map
  function initializeMap() {
    // Try to get user's location
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        position => {
          userLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };
          createMap();
        },
        error => {
          // Default location if geolocation fails (Dhaka city center)
          userLocation = {
            lat: 23.8103,
            lng: 90.4125
          };
          createMap();
        }
      );
    } else {
      // Default location if geolocation is not supported
      userLocation = {
        lat: 23.8103,
        lng: 90.4125
      };
      createMap();
    }
  }

  // Function to create map
  function createMap() {
    // Check if map container exists
    const mapContainer = document.getElementById('map');
    if (!mapContainer) return;

    // Create map centered at user's location
    map = L.map('map').setView([userLocation.lat, userLocation.lng], 13);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add user location marker
    L.marker([userLocation.lat, userLocation.lng])
      .addTo(map)
      .bindPopup(isBangla ? 'আপনার অবস্থান' : 'Your Location')
      .openPopup();

    // Load hospitals from API or use mock data
    loadHospitals();
  }

  // Function to load hospitals
  function loadHospitals() {
    // Show loading spinner
    const hospitalList = document.getElementById('hospital-list');
    if (hospitalList) {
      hospitalList.innerHTML = '<div class="spinner"></div>';
    }

    // Try to fetch hospitals from API
    fetch(BASE_URL + 'api/hospitals/nearby?lat=' + userLocation.lat + '&lng=' + userLocation.lng)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        displayHospitals(data);
      })
      .catch(error => {
        console.error('Error fetching hospitals:', error);
        // Use mock data if API fails
        displayHospitals(getMockHospitals());
      });
  }

  // Function to display hospitals on map and in list
  function displayHospitals(hospitals) {
    const hospitalList = document.getElementById('hospital-list');
    if (!hospitalList) return;

    // Clear loading spinner
    hospitalList.innerHTML = '';

    // If no hospitals found, show a message
    if (hospitals.length === 0) {
      hospitalList.innerHTML = `<p class="text-center">${isBangla ? 'আপনার অবস্থানের কাছাকাছি কোন হাসপাতাল পাওয়া যায়নি।' : 'No hospitals found near your location.'}</p>`;
      return;
    }

    // Add hospital markers and create list
    hospitals.forEach(hospital => {
      // Add marker to map
      const marker = L.marker([hospital.lat, hospital.lng])
        .addTo(map)
        .bindPopup(`
                    <b>${hospital.name}</b><br>
                    ${hospital.address}<br>
                    ${isBangla ? 'দূরত্ব: ' : 'Distance: '}${hospital.distance.toFixed(2)} km<br>
                    ${isBangla ? 'ফোন: ' : 'Phone: '}${hospital.phone}
                `);

      // Create hospital card for the list
      const hospitalCard = document.createElement('div');
      hospitalCard.className = 'hospital-card';
      hospitalCard.innerHTML = `
                <div class="hospital-name">${hospital.name}</div>
                <div class="hospital-address"><i class="fas fa-map-marker-alt me-2"></i>${hospital.address}</div>
                <div class="hospital-distance"><i class="fas fa-route me-2"></i>${isBangla ? 'দূরত্ব: ' : 'Distance: '}${hospital.distance.toFixed(2)} km</div>
                <button class="hospital-details-btn" onclick="showHospitalDetails('${hospital.id}')">${isBangla ? 'বিস্তারিত দেখুন' : 'View Details'}</button>
            `;
      hospitalList.appendChild(hospitalCard);

      // Highlight marker when hovering over hospital card
      hospitalCard.addEventListener('mouseenter', () => {
        marker.openPopup();
      });

      hospitalCard.addEventListener('mouseleave', () => {
        marker.closePopup();
      });

      // Center map on hospital when clicking on hospital card
      hospitalCard.addEventListener('click', () => {
        map.setView([hospital.lat, hospital.lng], 15);
      });
    });
  }

  // Function to get mock hospital data
  function getMockHospitals() {
    return [
      {
        id: 1,
        name: "United Hospital Limited",
        address: "Plot 15, Road 71, Gulshan, Dhaka 1212",
        lat: 23.7810,
        lng: 90.4150,
        phone: "+880 2-55034567",
        distance: calculateDistance(userLocation.lat, userLocation.lng, 23.7810, 90.4150)
      },
      {
        id: 2,
        name: "Popular Diagnostic Centre",
        address: "House 16, Road 2, Dhanmondi, Dhaka 1205",
        lat: 23.7465,
        lng: 90.3760,
        phone: "+880 2-55012345",
        distance: calculateDistance(userLocation.lat, userLocation.lng, 23.7465, 90.3760)
      },
      {
        id: 3,
        name: "Ibn Sina Diagnostic & Consultation Center",
        address: "House 48, Road 27, Block K, Banani, Dhaka 1213",
        lat: 23.7925,
        lng: 90.4065,
        phone: "+880 2-55098765",
        distance: calculateDistance(userLocation.lat, userLocation.lng, 23.7925, 90.4065)
      },
      {
        id: 4,
        name: "Labaid Specialized Hospital",
        address: "House 78, Road 11/A, Dhanmondi, Dhaka 1209",
        lat: 23.7490,
        lng: 90.3725,
        phone: "+880 2-55024680",
        distance: calculateDistance(userLocation.lat, userLocation.lng, 23.7490, 90.3725)
      },
      {
        id: 5,
        name: "Bangabandhu Sheikh Mujib Medical University",
        address: "Shahbag, Dhaka 1000",
        lat: 23.7380,
        lng: 90.3940,
        phone: "+880 2-55013579",
        distance: calculateDistance(userLocation.lat, userLocation.lng, 23.7380, 90.3940)
      },
      {
        id: 6,
        name: "Evercare Hospital Dhaka",
        address: "Plot 81, Block E, Bashundhara R/A, Dhaka 1229",
        lat: 23.8125,
        lng: 90.4250,
        phone: "+880 2-55011223",
        distance: calculateDistance(userLocation.lat, userLocation.lng, 23.8125, 90.4250)
      }
    ].filter(hospital => hospital.distance >= 2 && hospital.distance <= 10)
      .sort((a, b) => a.distance - b.distance);
  }

  // Function to calculate distance between two points in km
  function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of the earth in km
    const dLat = deg2rad(lat2 - lat1);
    const dLon = deg2rad(lon2 - lon1);
    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
      Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const d = R * c; // Distance in km
    return d;
  }

  function deg2rad(deg) {
    return deg * (Math.PI / 180);
  }

  // Function to show hospital details
  window.showHospitalDetails = function (hospitalId) {
    // In a real application, this would fetch hospital details from the API
    // For demo purposes, we'll just show an alert
    alert(isBangla ? `হাসপাতালের বিস্তারিত দেখানো হচ্ছে। একটি প্রকৃত অ্যাপ্লিকেশনে, এটি একটি বিস্তারিত দৃশ্য খুলবে।` : `Showing details for hospital ${hospitalId}. In a real application, this would open a detailed view.`);
  }

  // Check if language is Bangla
  const isBangla = getCookie('language') === 'bn';
});

// Global variables
let map;
let userLocation;