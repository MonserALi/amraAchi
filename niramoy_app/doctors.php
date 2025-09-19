<?php
require __DIR__ . '/inc/head.php';
require __DIR__ . '/inc/header.php';
?>
<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Doctors</h2>
    <div>
      <input id="doctorSearch" class="form-control" placeholder="Search specialization or name" style="min-width:300px;">
    </div>
  </div>

  <div id="doctorsList" class="row g-3"></div>

  <nav class="mt-4">
    <ul id="doctorsPagination" class="pagination"></ul>
  </nav>
</div>

<?php require __DIR__ . '/inc/footer.php'; ?>

<script>
  // Fetch and render paginated doctors
  const doctorsList = document.getElementById('doctorsList');
  const doctorsPagination = document.getElementById('doctorsPagination');
  const doctorSearch = document.getElementById('doctorSearch');

  let currentPage = 1,
    perPage = 12;
  // prefill specialization from query param
  const urlParams = new URLSearchParams(window.location.search);
  const initialSpec = urlParams.get('specialization') || '';
  if (initialSpec) doctorSearch.value = initialSpec;

  async function fetchDoctors(page = 1, q = '') {
    const params = new URLSearchParams({
      page,
      per_page: perPage
    });
    if (q) params.set('specialization', q);
    const res = await fetch('/niramoy_app/api.php?q=doctors&' + params.toString());
    const data = await res.json();
    renderDoctors(data);
  }

  function renderDoctors(data) {
    doctorsList.innerHTML = '';
    (data.doctors || []).forEach(d => {
      const div = document.createElement('div');
      div.className = 'col-md-4';
      div.innerHTML = `
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">${d.name}</h5>
          <p class="card-text">${d.specialization || ''}</p>
          <p class="mb-0">Fee: ${d.consultation_fee || 'N/A'}</p>
          <p class="mt-2">Rating: <strong>${d.avg_rating || 0}</strong> / 5</p>
          <p>Departments: ${d.departments ? d.departments.replace(/,/g, ', ') : '—'}</p>
          <div class="mt-2">
            <label for="rating-${d.id}">Rate:</label>
            <select id="rating-${d.id}" class="form-select form-select-sm d-inline-block" style="width:80px;">
              <option value="">-</option>
              <option value="5">5</option>
              <option value="4">4</option>
              <option value="3">3</option>
              <option value="2">2</option>
              <option value="1">1</option>
            </select>
            <button class="btn btn-sm btn-primary ms-2" onclick="submitRating(${d.id})">Submit</button>
          </div>
        </div>
      </div>
    `;
      doctorsList.appendChild(div);
    });

    // pagination
    doctorsPagination.innerHTML = '';
    const total = data.total || 0;
    const totalPages = data.total_pages || 1;
    for (let i = 1; i <= totalPages; i++) {
      const li = document.createElement('li');
      li.className = 'page-item' + (i === data.page ? ' active' : '');
      li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
      li.addEventListener('click', (e) => {
        e.preventDefault();
        fetchDoctors(i, doctorSearch.value);
      });
      doctorsPagination.appendChild(li);
    }
  }

  doctorSearch.addEventListener('keyup', (e) => {
    if (e.key === 'Enter') fetchDoctors(1, doctorSearch.value);
  });

  fetchDoctors(1, initialSpec);

  async function submitRating(doctorId) {
    const sel = document.getElementById('rating-' + doctorId);
    const val = sel ? sel.value : null;
    if (!val) {
      alert('Choose rating');
      return;
    }
    try {
      const res = await fetch('/niramoy_app/api.php?q=doctors/rate', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          doctor_id: doctorId,
          rating: parseInt(val)
        })
      });
      const data = await res.json();
      if (res.status === 201) {
        alert('Thank you for rating');
        fetchDoctors(currentPage, doctorSearch.value);
      } else alert(data.error || 'Could not submit rating');
    } catch (e) {
      console.error(e);
      alert('Request failed');
    }
  }
</script>