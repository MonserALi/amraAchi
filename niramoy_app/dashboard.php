<?php require_once __DIR__ . '/inc/config.php'; ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Niramoy Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <style>
    #map {
      height: 400px;
    }

    .hospital-item {
      cursor: pointer;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Niramoy Dashboard</a>
    </div>
  </nav>

  <div class="container py-4">
    <div class="row mb-3">
      <div class="col-md-8">
        <h3>Epidemic Alert</h3>
        <div id="alertBox" class="p-3 rounded bg-light border">Loading alert...</div>
      </div>
      <div class="col-md-4 text-end">
        <button id="locateBtn" class="btn btn-primary">Use My Location</button>
      </div>
    </div>

    <div class="row">
      <div class="col-md-8">
        <div id="map" class="mb-3"></div>
      </div>
      <div class="col-md-4">
        <h5>Nearby Hospitals</h5>
        <div id="hospList" class="list-group"></div>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    let map = L.map('map').setView([23.7806, 90.2794], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19
    }).addTo(map);

    let markers = L.layerGroup().addTo(map);

    function showHospitals(hospitals) {
      markers.clearLayers();
      const list = document.getElementById('hospList');
      list.innerHTML = '';
      hospitals.forEach(h => {
        if (h.latitude && h.longitude) {
          const m = L.marker([h.latitude, h.longitude]).addTo(markers).bindPopup('<b>' + h.name + '</b><br/>' + h.address + '<br/>' + (h.phone || ''));
          m.hospitalId = h.id;
        }
        const item = document.createElement('a');
        item.className = 'list-group-item list-group-item-action hospital-item';
        item.innerHTML = '<b>' + h.name + '</b><br/><small>' + h.address + '<br/>' + (h.phone || 'No phone') + '</small>';
        item.addEventListener('click', () => {
          if (h.latitude && h.longitude) map.setView([h.latitude, h.longitude], 15);
        });
        list.appendChild(item);
      });
    }

    function showAlert(data) {
      const box = document.getElementById('alertBox');
      if (data.alert === 'low') {
        box.className = 'p-3 rounded bg-success text-white';
        box.textContent = 'Low case count in nearby hospitals: ' + data.cases;
      } else if (data.alert === 'medium') {
        box.className = 'p-3 rounded bg-warning';
        box.textContent = 'Medium case count in nearby hospitals: ' + data.cases;
      } else if (data.alert === 'high') {
        box.className = 'p-3 rounded bg-danger text-white';
        box.textContent = 'High case count in nearby hospitals: ' + data.cases;
      } else {
        box.className = 'p-3 rounded bg-light';
        box.textContent = 'No nearby hospitals or no recent cases.';
      }
    }

    function fetchNearby(lat, lng) {
      fetch(`api.php?q=nearby_hospitals&lat=${lat}&lng=${lng}&radius=10`).then(r => r.json()).then(d => {
        showHospitals(d.hospitals || []);
      });
      fetch(`api.php?q=epidemic_alert&lat=${lat}&lng=${lng}&radius=10&days=14`).then(r => r.json()).then(showAlert);
    }

    document.getElementById('locateBtn').addEventListener('click', () => {
      if (!navigator.geolocation) return alert('Geolocation not supported');
      navigator.geolocation.getCurrentPosition(p => {
        const lat = p.coords.latitude,
          lng = p.coords.longitude;
        map.setView([lat, lng], 13);
        L.circle([lat, lng], {
          radius: 1000,
          color: '#007bff'
        }).addTo(map);
        fetchNearby(lat, lng);
      }, err => alert('Unable to get location: ' + err.message));
    });

    // Load default nearby (center of Dhaka)
    fetchNearby(23.7806, 90.2794);
  </script>
</body>

</html>