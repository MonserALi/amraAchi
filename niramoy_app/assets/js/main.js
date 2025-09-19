// Main JS for AmraAchi: theme toggle and simple UI helpers
(function () {
  const THEME_KEY = 'amraachi_theme';
  function applyTheme(name) {
    if (name === 'dark') document.documentElement.classList.add('dark');
    else document.documentElement.classList.remove('dark');
  }
  function getTheme() { return localStorage.getItem(THEME_KEY) || 'light'; }
  function toggleTheme() { const next = getTheme() === 'dark' ? 'light' : 'dark'; localStorage.setItem(THEME_KEY, next); applyTheme(next); updateToggleIcon(); }
  function updateToggleIcon() { const btn = document.getElementById('themeToggle'); if (!btn) return; btn.innerHTML = getTheme() === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>'; }
  // Init
  applyTheme(getTheme());
  document.addEventListener('DOMContentLoaded', () => { updateToggleIcon(); const btn = document.getElementById('themeToggle'); if (btn) btn.addEventListener('click', toggleTheme); });
  window.toggleTheme = toggleTheme; // export for inline handlers
})();

// --- page UI: language toggle and map handling ---
(function () {
  let isBangla = false;
  document.addEventListener('DOMContentLoaded', () => {
    const langToggle = document.getElementById('langToggle');
    if (langToggle) {
      langToggle.addEventListener('click', () => {
        isBangla = !isBangla;
        document.querySelectorAll('.en-text').forEach(t => t.style.display = isBangla ? 'none' : 'inline');
        document.querySelectorAll('.bn-text').forEach(t => t.style.display = isBangla ? 'inline' : 'none');
        langToggle.textContent = isBangla ? 'English' : 'বাংলা';
      });
    }

    // initialize map when Leaflet is available
    function initAppMap() {
      if (typeof L === 'undefined') return; // Leaflet not loaded yet
      const map = L.map('map').setView([23.7806, 90.2794], 12);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
      const markers = L.layerGroup().addTo(map);

      function showHospitals(hospitals) {
        markers.clearLayers();
        const list = document.getElementById('hospital-list'); if (!list) return; list.innerHTML = '';
        hospitals.forEach(h => {
          if (h.latitude && h.longitude) {
            const m = L.marker([h.latitude, h.longitude]).addTo(markers).bindPopup('<b>' + h.name + '</b><br/>' + h.address + '<br/>' + (h.phone || ''));
            m.hospitalId = h.id;
          }
          const item = document.createElement('a'); item.className = 'list-group-item list-group-item-action hospital-item';
          item.innerHTML = '<b>' + h.name + '</b><br/><small>' + h.address + '<br/>' + (h.phone || 'No phone') + '</small>';
          item.addEventListener('click', () => { if (h.latitude && h.longitude) map.setView([h.latitude, h.longitude], 15); });
          list.appendChild(item);
        });
      }

      function showAlert(data) {
        const box = document.getElementById('epidemicBanner'); if (!box) return;
        if (data.alert === 'low') { box.style.background = '#d4edda'; box.style.color = '#155724'; }
        else if (data.alert === 'medium') { box.style.background = '#fff3cd'; box.style.color = '#856404'; }
        else if (data.alert === 'high') { box.style.background = '#f8d7da'; box.style.color = '#721c24'; }
        else { box.style.background = ''; box.style.color = ''; }
      }

      function fetchNearby(lat, lng) {
        fetch(`api.php?q=nearby_hospitals&lat=${lat}&lng=${lng}&radius=10`).then(r => r.json()).then(d => showHospitals(d.hospitals || []));
        fetch(`api.php?q=epidemic_alert&lat=${lat}&lng=${lng}&radius=10&days=14`).then(r => r.json()).then(showAlert);
      }

      const locateBtn = document.createElement('button'); locateBtn.id = 'locateBtn'; locateBtn.className = 'btn btn-primary mb-3'; locateBtn.textContent = isBangla ? 'আমার লোকেশন' : 'Use My Location';
      locateBtn.addEventListener('click', () => {
        if (!navigator.geolocation) return alert('Geolocation not supported');
        navigator.geolocation.getCurrentPosition(p => { const lat = p.coords.latitude, lng = p.coords.longitude; map.setView([lat, lng], 13); L.circle([lat, lng], { radius: 1000, color: '#007bff' }).addTo(map); fetchNearby(lat, lng); }, err => alert('Unable to get location: ' + err.message));
      });
      const mapCol = document.querySelector('#map-section .col-lg-8') || document.querySelector('#map-section .col-md-8');
      if (mapCol) {
        // insert at top of the map column
        const firstChild = mapCol.firstElementChild;
        mapCol.insertBefore(locateBtn, firstChild);
      } else {
        const mapSection = document.getElementById('map-section'); if (mapSection) mapSection.insertBefore(locateBtn, mapSection.firstChild);
      }
      fetchNearby(23.7806, 90.2794);
    }

    // Try init map immediately if Leaflet loaded, otherwise wait a bit
    if (typeof L !== 'undefined') initAppMap(); else {
      const check = setInterval(() => { if (typeof L !== 'undefined') { clearInterval(check); initAppMap(); } }, 200);
    }

    // --- Departments & Doctors preview rendering ---
    const departmentsData = [
      { name: 'Cardiology', img: 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=2070&q=80', desc: 'Comprehensive care for heart conditions with state-of-the-art facilities.' },
      { name: 'Neurology', img: 'https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?auto=format&fit=crop&w=2070&q=80', desc: 'Diagnosis and treatment for brain and nervous system disorders.' },
      { name: 'Pediatrics', img: 'https://images.unsplash.com/photo-1579684385127-acec1938f2d7?auto=format&fit=crop&w=2070&q=80', desc: 'Healthcare services for infants, children, and adolescents.' },
      { name: 'Orthopedics', img: 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=2070&q=80', desc: 'Specialized care for bones, joints, ligaments, and muscles.' },
      { name: 'Gynecology', img: 'https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?auto=format&fit=crop&w=2070&q=80', desc: 'Complete women\'s healthcare services from routine check-ups to specialized treatments.' },
      { name: 'Radiology', img: 'https://images.unsplash.com/photo-1579684385127-acec1938f2d7?auto=format&fit=crop&w=2070&q=80', desc: 'Advanced imaging services for accurate diagnosis.' }
    ];

    const doctorsData = [
      { name: 'Dr. Ahmed Khan', specialty: 'Cardiologist', img: 'https://randomuser.me/api/portraits/men/32.jpg', bio: '15 years of experience in interventional cardiology.' },
      { name: 'Dr. Fatima Rahman', specialty: 'Neurologist', img: 'https://randomuser.me/api/portraits/women/44.jpg', bio: 'Specialized in stroke treatment and neurodegenerative disorders.' },
      { name: 'Dr. Mohammad Ali', specialty: 'Pediatrician', img: 'https://randomuser.me/api/portraits/men/67.jpg', bio: 'Comprehensive care for children of all ages.' },
      { name: 'Dr. Nusrat Jahan', specialty: 'Gynecologist', img: 'https://randomuser.me/api/portraits/women/68.jpg', bio: 'Expert in high-risk pregnancies and minimally invasive surgeries.' }
    ];

    function renderDepartments(list) {
      const scroller = document.getElementById('departmentsScroller');
      if (!scroller) return;
      scroller.innerHTML = '';
      list.forEach(d => {
        const card = document.createElement('div');
        card.className = 'department-card';
        card.innerHTML = `
          <div class="department-img-wrapper">
            <img src="${d.img}" class="department-img" alt="${d.name}">
          </div>
          <div class="department-content p-3">
            <h3>${d.name}</h3>
            <p class="mb-2">${d.desc}</p>
            <button class="department-btn">Learn More</button>
          </div>
        `;
        scroller.appendChild(card);
      });
    }

    function renderDoctors(list) {
      const target = document.getElementById('doctorsPreview');
      if (!target) return;
      target.innerHTML = '';
      list.forEach(d => {
        const col = document.createElement('div'); col.className = 'col-md-6 col-lg-3';
        col.innerHTML = `
          <div class="doctor-card">
            <img src="${d.img}" alt="${d.name}" class="doctor-img">
            <div class="doctor-content p-3">
              <h4 class="doctor-name">${d.name}</h4>
              <p class="doctor-specialty">${d.specialty}</p>
              <p class="doctor-bio">${d.bio}</p>
              <div class="doctor-social mb-2">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
              </div>
              <button class="doctor-btn">View Profile</button>
            </div>
          </div>
        `;
        target.appendChild(col);
      });
    }

    // initial render
    renderDepartments(departmentsData);
    renderDoctors(doctorsData);

    // Search behavior: filter departments and doctors
    const searchInput = document.querySelector('.search-input');
    const searchBtn = document.querySelector('.search-btn');
    function performSearch() {
      const q = (searchInput && searchInput.value || '').trim().toLowerCase();
      if (!q) { renderDepartments(departmentsData); renderDoctors(doctorsData); return; }
      const fd = departmentsData.filter(d => d.name.toLowerCase().includes(q) || d.desc.toLowerCase().includes(q));
      const fk = doctorsData.filter(d => d.name.toLowerCase().includes(q) || d.specialty.toLowerCase().includes(q) || d.bio.toLowerCase().includes(q));
      renderDepartments(fd.length ? fd : departmentsData);
      renderDoctors(fk.length ? fk : doctorsData);
    }
    if (searchBtn) searchBtn.addEventListener('click', performSearch);
    if (searchInput) searchInput.addEventListener('keydown', (e) => { if (e.key === 'Enter') performSearch(); });

    // Scroller controls
    const scroller = document.getElementById('departmentsScroller');
    const leftBtn = document.getElementById('scrollLeft');
    const rightBtn = document.getElementById('scrollRight');
    if (leftBtn && scroller) leftBtn.addEventListener('click', () => { scroller.scrollBy({ left: -300, behavior: 'smooth' }); });
    if (rightBtn && scroller) rightBtn.addEventListener('click', () => { scroller.scrollBy({ left: 300, behavior: 'smooth' }); });
  });
})();
