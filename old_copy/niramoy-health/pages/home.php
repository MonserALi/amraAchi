<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../config.php';

// Get featured hospitals
function getFeaturedHospitals($limit = 6)
{
  $database = new Database();
  $db = $database->getConnection();

  $query = "SELECT h.id, h.name, h.address, h.district, h.division, h.phone, 
              h.hospital_type, h.latitude, h.longitude 
              FROM hospitals h 
              WHERE h.is_active = 1 
              ORDER BY h.id DESC 
              LIMIT :limit";

  $stmt = $db->prepare($query);
  $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get top-rated doctors
function getTopRatedDoctors($limit = 6)
{
  $database = new Database();
  $db = $database->getConnection();

  $query = "SELECT d.id, d.specialization, d.experience_years, d.consultation_fee, 
              u.name, u.profile_image 
              FROM doctors d 
              JOIN users u ON d.user_id = u.id 
              WHERE d.is_verified = 1 
              ORDER BY d.experience_years DESC, d.consultation_fee ASC 
              LIMIT :limit";

  $stmt = $db->prepare($query);
  $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get current epidemics
function getCurrentEpidemics($limit = 3)
{
  $database = new Database();
  $db = $database->getConnection();

  $query = "SELECT id, name, description, symptoms, prevention, treatment 
              FROM diseases 
              WHERE is_epidemic = 1 
              ORDER BY id DESC 
              LIMIT :limit";

  $stmt = $db->prepare($query);
  $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get doctor specializations
function getDoctorSpecializations()
{
  $database = new Database();
  $db = $database->getConnection();

  $query = "SELECT DISTINCT specialization 
              FROM doctors 
              WHERE is_verified = 1 
              ORDER BY specialization";

  $stmt = $db->prepare($query);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Get hospital locations
function getHospitalLocations()
{
  $database = new Database();
  $db = $database->getConnection();

  $query = "SELECT DISTINCT district 
              FROM hospitals 
              WHERE is_active = 1 
              ORDER BY district";

  $stmt = $db->prepare($query);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

$featuredHospitals = getFeaturedHospitals();
$topRatedDoctors = getTopRatedDoctors();
$currentEpidemics = getCurrentEpidemics();
$specializations = getDoctorSpecializations();
$locations = getHospitalLocations();
?>

<!-- Hero Section -->
<section class="hero-section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="hero-content">
          <h1><?php echo $lang['welcome']; ?></h1>
          <p><?php echo $lang['welcome_message']; ?></p>
          <div class="hero-buttons">
            <a href="#doctors" class="btn btn-primary"><?php echo $lang['find_doctor']; ?></a>
            <a href="#hospitals" class="btn btn-outline-light"><?php echo $lang['find_hospital']; ?></a>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="hero-image">
          <img src="assets/images/hero-image.png" alt="<?php echo $lang['welcome']; ?>">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Epidemic Alert Section -->
<section class="epidemic-section">
  <div class="container">
    <div class="section-header">
      <h2><?php echo $lang['epidemic_alert']; ?></h2>
      <div class="divider"></div>
    </div>
    <div class="row">
      <?php foreach ($currentEpidemics as $epidemic): ?>
        <div class="col-lg-4 col-md-6">
          <div class="epidemic-card">
            <div class="epidemic-icon">
              <i class="fas fa-virus"></i>
            </div>
            <h3><?php echo $epidemic['name']; ?></h3>
            <p><?php echo $epidemic['description']; ?></p>
            <div class="epidemic-details">
              <div class="detail-item">
                <h4><?php echo $lang['symptoms']; ?>:</h4>
                <p><?php echo $epidemic['symptoms']; ?></p>
              </div>
              <div class="detail-item">
                <h4><?php echo $lang['prevention']; ?>:</h4>
                <p><?php echo $epidemic['prevention']; ?></p>
              </div>
            </div>
            <a href="#" class="btn btn-sm btn-outline-danger"><?php echo $lang['learn_more']; ?></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Search Doctors Section -->
<section id="doctors" class="search-section">
  <div class="container">
    <div class="section-header">
      <h2><?php echo $lang['search_doctors']; ?></h2>
      <div class="divider"></div>
    </div>
    <div class="search-container">
      <div class="search-tabs">
        <ul class="nav nav-tabs" id="searchTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="specialization-tab" data-bs-toggle="tab" data-bs-target="#specialization" type="button" role="tab" aria-controls="specialization" aria-selected="true"><?php echo $lang['search_by_specialization']; ?></button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button" role="tab" aria-controls="location" aria-selected="false"><?php echo $lang['search_by_location']; ?></button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="name-tab" data-bs-toggle="tab" data-bs-target="#name" type="button" role="tab" aria-controls="name" aria-selected="false"><?php echo $lang['search_by_name']; ?></button>
          </li>
        </ul>
      </div>
      <div class="tab-content" id="searchTabsContent">
        <div class="tab-pane fade show active" id="specialization" role="tabpanel" aria-labelledby="specialization-tab">
          <div class="search-form">
            <div class="row">
              <div class="col-md-10">
                <select class="form-select" id="specialization-select">
                  <option value=""><?php echo $lang['select_specialization']; ?></option>
                  <?php foreach ($specializations as $spec): ?>
                    <option value="<?php echo $spec; ?>"><?php echo $spec; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2">
                <button class="btn btn-primary w-100"><?php echo $lang['search']; ?></button>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
          <div class="search-form">
            <div class="row">
              <div class="col-md-10">
                <select class="form-select" id="location-select">
                  <option value=""><?php echo $lang['select_location']; ?></option>
                  <?php foreach ($locations as $loc): ?>
                    <option value="<?php echo $loc; ?>"><?php echo $loc; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2">
                <button class="btn btn-primary w-100"><?php echo $lang['search']; ?></button>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="name" role="tabpanel" aria-labelledby="name-tab">
          <div class="search-form">
            <div class="row">
              <div class="col-md-10">
                <input type="text" class="form-control" id="doctor-name" placeholder="<?php echo $lang['enter_doctor_name']; ?>">
              </div>
              <div class="col-md-2">
                <button class="btn btn-primary w-100"><?php echo $lang['search']; ?></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="search-results" id="doctor-search-results">
      <?php
      // Handle search
      $searchResults = [];
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $database = new Database();
        $db = $database->getConnection();
        if (!empty($_POST['specialization'])) {
          $spec = $_POST['specialization'];
          $stmt = $db->prepare("SELECT d.id, d.specialization, d.experience_years, d.consultation_fee, u.name, u.profile_image FROM doctors d JOIN users u ON d.user_id = u.id WHERE d.specialization = :spec AND d.is_verified = 1");
          $stmt->bindParam(':spec', $spec);
          $stmt->execute();
          $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif (!empty($_POST['location'])) {
          $loc = $_POST['location'];
          $stmt = $db->prepare("SELECT d.id, d.specialization, d.experience_years, d.consultation_fee, u.name, u.profile_image FROM doctors d JOIN users u ON d.user_id = u.id WHERE d.location = :loc AND d.is_verified = 1");
          $stmt->bindParam(':loc', $loc);
          $stmt->execute();
          $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif (!empty($_POST['doctor_name'])) {
          $name = '%' . $_POST['doctor_name'] . '%';
          $stmt = $db->prepare("SELECT d.id, d.specialization, d.experience_years, d.consultation_fee, u.name, u.profile_image FROM doctors d JOIN users u ON d.user_id = u.id WHERE u.name LIKE :name AND d.is_verified = 1");
          $stmt->bindParam(':name', $name);
          $stmt->execute();
          $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
      }
      if (!empty($searchResults)) {
        echo '<div class="row">';
        foreach ($searchResults as $doctor) {
          echo '<div class="col-lg-4 col-md-6">';
          echo '<div class="doctor-card">';
          echo '<div class="doctor-image"><img src="assets/images/doctors/' . ($doctor['profile_image'] ?: 'default-doctor.png') . '" alt="' . htmlspecialchars($doctor['name']) . '"></div>';
          echo '<div class="doctor-info">';
          echo '<h3>' . htmlspecialchars($doctor['name']) . '</h3>';
          echo '<p class="specialization">' . htmlspecialchars($doctor['specialization']) . '</p>';
          echo '<div class="doctor-meta">';
          echo '<span><i class="fas fa-briefcase"></i> ' . htmlspecialchars($doctor['experience_years']) . ' ' . $lang['experience'] . '</span>';
          echo '<span><i class="fas fa-money-bill-wave"></i> ' . htmlspecialchars($doctor['consultation_fee']) . ' BDT</span>';
          echo '</div>';
          echo '<div class="doctor-actions">';
          echo '<a href="#" class="btn btn-sm btn-outline-primary">' . $lang['view_profile'] . '</a>';
          echo '<a href="#" class="btn btn-sm btn-primary">' . $lang['book_now'] . '</a>';
          echo '</div>';
          echo '</div></div></div>';
        }
        echo '</div>';
      } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo '<div class="alert alert-warning">' . $lang['no_hospitals_found'] . '</div>';
      }
      ?>
    </div>
  </div>
</section>

<!-- Top Rated Doctors Section -->
<section class="doctors-section">
  <div class="container">
    <div class="section-header">
      <h2><?php echo $lang['top_rated_doctors']; ?></h2>
      <div class="divider"></div>
      <div class="view-all">
        <a href="#" class="btn btn-outline-primary"><?php echo $lang['view_all_doctors']; ?></a>
      </div>
    </div>
    <div class="row">
      <?php foreach ($topRatedDoctors as $doctor): ?>
        <div class="col-lg-4 col-md-6">
          <div class="doctor-card">
            <div class="doctor-image">
              <img src="assets/images/doctors/<?php echo $doctor['profile_image'] ?: 'default-doctor.png'; ?>" alt="<?php echo $doctor['name']; ?>">
            </div>
            <div class="doctor-info">
              <h3><?php echo $doctor['name']; ?></h3>
              <p class="specialization"><?php echo $doctor['specialization']; ?></p>
              <div class="doctor-meta">
                <span><i class="fas fa-briefcase"></i> <?php echo $doctor['experience_years']; ?> <?php echo $lang['experience']; ?></span>
                <span><i class="fas fa-money-bill-wave"></i> <?php echo $doctor['consultation_fee']; ?> BDT</span>
              </div>
              <div class="doctor-actions">
                <a href="#" class="btn btn-sm btn-outline-primary"><?php echo $lang['view_profile']; ?></a>
                <a href="#" class="btn btn-sm btn-primary"><?php echo $lang['book_now']; ?></a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Nearby Hospitals Section -->
<section id="hospitals" class="hospitals-section">
  <div class="container">
    <div class="section-header">
      <h2><?php echo $lang['nearest_hospitals']; ?></h2>
      <div class="divider"></div>
      <div class="view-all">
        <a href="#" class="btn btn-outline-primary"><?php echo $lang['view_all_hospitals']; ?></a>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8">
        <div class="map-container">
          <div id="hospital-map" class="hospital-map"></div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="hospital-list">
          <h3><?php echo $lang['nearest_hospitals']; ?></h3>
          <div class="list-container" id="hospital-list">
            <div class="loading-spinner">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden"><?php echo $lang['loading']; ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Services Section -->
<section class="services-section">
  <div class="container">
    <div class="section-header">
      <h2><?php echo $lang['our_services']; ?></h2>
      <div class="divider"></div>
    </div>
    <div class="row">
      <div class="col-lg-3 col-md-6">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-ambulance"></i>
          </div>
          <h3><?php echo $lang['service1']; ?></h3>
          <p><?php echo $lang['service1_desc']; ?></p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-user-md"></i>
          </div>
          <h3><?php echo $lang['service2']; ?></h3>
          <p><?php echo $lang['service2_desc']; ?></p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-procedures"></i>
          </div>
          <h3><?php echo $lang['service3']; ?></h3>
          <p><?php echo $lang['service3_desc']; ?></p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-file-medical"></i>
          </div>
          <h3><?php echo $lang['service4']; ?></h3>
          <p><?php echo $lang['service4_desc']; ?></p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
  <div class="container">
    <div class="section-header">
      <h2><?php echo $lang['testimonials']; ?></h2>
      <div class="divider"></div>
    </div>
    <div class="row">
      <div class="col-lg-4">
        <div class="testimonial-card">
          <div class="testimonial-content">
            <p><?php echo $lang['testimonial1']; ?></p>
          </div>
          <div class="testimonial-author">
            <div class="author-image">
              <img src="assets/images/patients/patient1.jpg" alt="Patient">
            </div>
            <div class="author-info">
              <h4>Rahim Khan</h4>
              <p>Patient</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="testimonial-card">
          <div class="testimonial-content">
            <p><?php echo $lang['testimonial2']; ?></p>
          </div>
          <div class="testimonial-author">
            <div class="author-image">
              <img src="assets/images/patients/patient2.jpg" alt="Patient">
            </div>
            <div class="author-info">
              <h4>Karima Begum</h4>
              <p>Patient</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="testimonial-card">
          <div class="testimonial-content">
            <p><?php echo $lang['testimonial3']; ?></p>
          </div>
          <div class="testimonial-author">
            <div class="author-image">
              <img src="assets/images/patients/patient3.jpg" alt="Patient">
            </div>
            <div class="author-info">
              <h4>Jamal Uddin</h4>
              <p>Patient</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- App Download Section -->
<section class="app-download-section">
  <div class="container">
    <div class="app-download-content">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="app-info">
            <h2><?php echo $lang['download_app']; ?></h2>
            <p><?php echo $lang['app_description']; ?></p>
            <div class="app-buttons">
              <a href="#"><img src="assets/images/google-play.png" alt="Google Play"></a>
              <a href="#"><img src="assets/images/app-store.png" alt="App Store"></a>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="app-image">
            <img src="assets/images/app-mockup.png" alt="App Mockup">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>