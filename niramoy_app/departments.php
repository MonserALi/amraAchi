<?php
require __DIR__ . '/inc/head.php';
require __DIR__ . '/inc/header.php';
?>
<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Departments</h2>
  </div>

  <div id="departmentsList" class="row g-3"></div>

  <nav class="mt-4">
    <ul id="departmentsPagination" class="pagination"></ul>
  </nav>
</div>

<?php require __DIR__ . '/inc/footer.php'; ?>

<script>
  const departmentsList = document.getElementById('departmentsList');
  const departmentsPagination = document.getElementById('departmentsPagination');
  let deptPage = 1,
    deptPer = 12;

  async function fetchDepartments(page = 1) {
    const params = new URLSearchParams({
      page,
      per_page: deptPer
    });
    const res = await fetch('/niramoy_app/api.php?q=departments&' + params.toString());
    const data = await res.json();
    renderDepartments(data);
  }

  function renderDepartments(data) {
    departmentsList.innerHTML = '';
    (data.departments || []).forEach(d => {
      const div = document.createElement('div');
      div.className = 'col-md-4';
      div.innerHTML = `
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"><a href="doctors.php?specialization=${encodeURIComponent(d.name)}">${d.name}</a></h5>
          <p class="mb-0">Doctors: ${d.doctor_count}</p>
        </div>
      </div>
    `;
      departmentsList.appendChild(div);
    });

    departmentsPagination.innerHTML = '';
    const totalPages = data.total_pages || 1;
    for (let i = 1; i <= totalPages; i++) {
      const li = document.createElement('li');
      li.className = 'page-item' + (i === data.page ? ' active' : '');
      li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
      li.addEventListener('click', (e) => {
        e.preventDefault();
        fetchDepartments(i);
      });
      departmentsPagination.appendChild(li);
    }
  }

  fetchDepartments();
</script>