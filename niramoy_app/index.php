<?php require_once __DIR__ . '/inc/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include __DIR__ . '/inc/head.php'; ?>
</head>

<body>
  <?php include __DIR__ . '/inc/header.php'; ?>
  <?php include __DIR__ . '/inc/sos.php'; ?>

  <!-- Hero Section -->
  <section id="home" class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="hero-content">
            <h1><span class="en-text">Your Complete Digital Healthcare Solution</span><span class="bn-text" style="display: none;">আপনার সম্পূর্ণ ডিজিটাল স্বাস্থ্যসেবা সমাধান</span></h1>
            <p><span class="en-text">AmraAchi connects patients, doctors, and healthcare services in one integrated platform for better health outcomes.</span><span class="bn-text" style="display: none;">আমরাআছি রোগী, ডাক্তার এবং স্বাস্থ্যসেবা সেবাকে একটি সমন্বিত প্ল্যাটফর্মে সংযুক্ত করে উন্নত স্বাস্থ্য ফলাফলের জন্য।</span></p>
            <div class="d-flex flex-wrap">
              <a href="#appointment" class="btn btn-primary-custom hero-btn"><span class="en-text">Book Appointment</span><span class="bn-text" style="display: none;">অ্যাপয়েন্টমেন্ট বুক করুন</span></a>
              <a href="#map-section" class="btn btn-outline-custom hero-btn"><span class="en-text">Find Hospitals</span><span class="bn-text" style="display: none;">হাসপাতাল খুঁজুন</span></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Epidemic Banner -->
  <div class="epidemic-banner" id="epidemicBanner">
    <div class="container">
      <div class="epidemic-content">
        <div class="epidemic-info">
          <div class="epidemic-icon">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
          <div class="epidemic-text">
            <h3><span class="en-text">COVID-19 Alert</span><span class="bn-text" style="display: none;">কোভিড-১৯ সতর্কতা</span></h3>
            <p><span class="en-text">Cases are rising. Protect yourself and others.</span><span class="bn-text" style="display: none;">কেস বাড়ছে। নিজেকে এবং অন্যদের রক্ষা করুন।</span></p>
          </div>
        </div>
        <div class="epidemic-actions">
          <button class="epidemic-learn-btn" onclick="showEpidemicDetails()"><span class="en-text">Learn More</span><span class="bn-text" style="display: none;">আরও জানুন</span></button>
          <button class="close-banner" id="closeBanner">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Map Section -->
  <section id="map-section" class="map-section">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="section-title"><span class="en-text">Find Hospitals Near You</span><span class="bn-text" style="display: none;">আপনার কাছাকাছি হাসপাতাল খুঁজুন</span></h2>
        <p class="section-subtitle"><span class="en-text">Locate the nearest hospitals and healthcare facilities with our interactive map</span><span class="bn-text" style="display: none;">আমাদের ইন্টারেক্টিভ মানচিত্রের সাথে নিকটস্থ হাসপাতাল এবং স্বাস্থ্যসেবা সুবিধা খুঁজুন</span></p>
      </div>
      <div class="row">
        <div class="col-lg-8 mb-4">
          <div id="map"></div>
        </div>
        <div class="col-lg-4">
          <div class="hospital-list" id="hospital-list"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Departments Section -->
  <section id="departments" class="py-5">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="section-title"><span class="en-text">Our Departments</span><span class="bn-text" style="display: none;">আমাদের বিভাগসমূহ</span></h2>
        <p class="section-subtitle"><span class="en-text">Comprehensive services across major specialties</span><span class="bn-text" style="display: none;">প্রধান বিশেষায়িত সেবাগুলোর সমন্বিত সেবা</span></p>
      </div>
      <div class="departments-container">
        <div class="departments-scroller" id="departmentsScroller">
          <!-- departments will be rendered here as horizontal scroller -->
        </div>
      </div>
      <div class="scroll-controls text-center mt-3">
        <button class="scroll-btn" id="scrollLeft"><i class="fas fa-chevron-left"></i></button>
        <button class="scroll-btn" id="scrollRight"><i class="fas fa-chevron-right"></i></button>
      </div>
      <div class="text-center mt-3"><a href="departments.php" class="btn btn-outline-primary">View All Departments</a></div>
    </div>
  </section>

  <!-- (Other sections: services, stats, doctors, appointment, testimonials) -->
  <!-- Services Section -->
  <section id="services" class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="section-title"><span class="en-text">Our Services</span><span class="bn-text" style="display: none;">আমাদের সেবা</span></h2>
        <p class="section-subtitle"><span class="en-text">High quality care across multiple specialties</span><span class="bn-text" style="display: none;">বিভিন্ন বিশেষায়িত এলাকায় উচ্চমানের যত্ন</span></p>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="service-card p-4 text-center">
            <i class="fas fa-briefcase-medical fa-2x mb-3 text-primary"></i>
            <h5>Outpatient Care</h5>
            <p>Consultations and follow-ups with specialists.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="service-card p-4 text-center">
            <i class="fas fa-hospital-user fa-2x mb-3 text-primary"></i>
            <h5>Inpatient Services</h5>
            <p>24/7 inpatient care and round-the-clock medical support.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="service-card p-4 text-center">
            <i class="fas fa-hand-holding-medical fa-2x mb-3 text-primary"></i>
            <h5>Home Care</h5>
            <p>Receive medical care in the comfort of your home.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section id="stats" class="py-5">
    <div class="container">
      <div class="row text-center">
        <div class="col-md-3">
          <h3 class="display-6">120+</h3>
          <p>Doctors</p>
        </div>
        <div class="col-md-3">
          <h3 class="display-6">10k+</h3>
          <p>Patients Treated</p>
        </div>
        <div class="col-md-3">
          <h3 class="display-6">24/7</h3>
          <p>Emergency Support</p>
        </div>
        <div class="col-md-3">
          <h3 class="display-6">50+</h3>
          <p>Departments</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Doctors Section -->
  <section id="doctors" class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="section-title"><span class="en-text">Our Doctors</span><span class="bn-text" style="display: none;">আমাদের ডাক্তার</span></h2>
        <p class="section-subtitle"><span class="en-text">Experienced and caring professionals</span><span class="bn-text" style="display: none;">অভিজ্ঞ এবং যত্নশীল পেশাদার</span></p>
      </div>
      <div id="doctorsPreview" class="row g-4"></div>
      <div class="text-center mt-3"><a href="doctors.php" class="btn btn-outline-primary">View All Doctors</a></div>
    </div>
  </section>

  <!-- Appointment Section removed as requested -->

  <!-- Testimonials Section -->
  <section id="testimonials" class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="section-title">Testimonials</h2>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="testimonial-card p-4 shadow-sm">"Great care and professional staff"<div class="mt-2"><strong>- A Patient</strong></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testimonial-card p-4 shadow-sm">"Quick response during emergency"<div class="mt-2"><strong>- B Patient</strong></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testimonial-card p-4 shadow-sm">"Excellent doctors and facilities"<div class="mt-2"><strong>- C Patient</strong></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php include __DIR__ . '/inc/footer.php'; ?>
</body>

<script>
  // Fetch previews for departments and doctors with fallback
  async function fetchPreviews() {
    try {
      // First try derived departments (from doctor specializations)
      const dRes = await fetch('/niramoy_app/api.php?q=departments&per_page=6');
      const dData = await dRes.json();
      let departments = dData.departments || [];

      // If derived list is empty, fetch full departments table as fallback
      if (!departments.length) {
        const listRes = await fetch('/niramoy_app/api.php?q=departments/list');
        const listData = await listRes.json();
        departments = (listData.departments || []).map(d => ({
          name: d.name,
          doctor_count: 0
        }));
      }

      // Render departments into the horizontal scroller
      const scroller = document.getElementById('departmentsScroller');
      scroller.innerHTML = '';
      departments.forEach(d => {
        const card = document.createElement('div');
        card.className = 'department-card p-3 text-center shadow-sm';
        card.innerHTML = `<div class="department-img-wrapper mb-2"><i class="fas fa-clinic-medical fa-2x text-primary"></i></div><h5 class="mb-1">${d.name}</h5><p class="mb-0 small">Doctors: ${d.doctor_count || '—'}</p>`;
        scroller.appendChild(card);
      });

      // Doctors preview
      const docRes = await fetch('/niramoy_app/api.php?q=doctors&per_page=6');
      const docData = await docRes.json();
      const dl = document.getElementById('doctorsPreview');
      dl.innerHTML = '';
      (docData.doctors || []).forEach(doc => {
        const col = document.createElement('div');
        col.className = 'col-md-4';
        col.innerHTML = `<div class="doctor-card p-3 text-center shadow-sm"><h5>${doc.name}</h5><p class="mb-0">${doc.specialization || ''}</p></div>`;
        dl.appendChild(col);
      });
    } catch (err) {
      console.error(err)
    }
  }
  fetchPreviews();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>
</body>

</html>