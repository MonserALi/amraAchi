<?php
// Start session only if headers not already sent. Some pages include this file after output.
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
  session_start();
}
// Refresh logged-in user data from DB to ensure header shows latest values
require_once __DIR__ . '/db.php';
if (!empty($_SESSION) && isset($_SESSION['user']['id'])) {
  try {
    $pdo = get_db();
    $uStmt = $pdo->prepare('SELECT id, name, email, profile_image FROM users WHERE id = :id LIMIT 1');
    $uStmt->execute([':id' => (int)$_SESSION['user']['id']]);
    $fresh = $uStmt->fetch();
    if ($fresh) {
      // Update session values so header reflects current DB values
      $_SESSION['user']['name'] = $fresh['name'];
      $_SESSION['user']['email'] = $fresh['email'];
      $_SESSION['user']['profile_image'] = $fresh['profile_image'];
    }
  } catch (Exception $ex) {
    error_log('inc/header.php refresh user error: ' . $ex->getMessage());
  }
}
// Top header + main navbar + search bar
?>
<!-- Top Header -->
<div class="top-header">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="contact-info">
          <span><i class="fas fa-phone-alt me-2"></i> <span class="en-text">+880 1234 567890</span><span class="bn-text" style="display: none;">+৮৮০ ১২৩৪ ৫৬৭৮৯০</span></span>
          <span><i class="fas fa-envelope me-2"></i> <span class="en-text">info@amraaichi.com</span><span class="bn-text" style="display: none;">info@amraaichi.com</span></span>
        </div>
      </div>
      <div class="col-md-6 text-end">
        <button class="lang-toggle" id="langToggle">বাংলা</button>
        <div class="social-icons d-inline-block ms-3">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Main Header -->
<header class="main-header">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="#">AmraAchi</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="#home"><span class="en-text">Home</span><span class="bn-text" style="display: none;">হোম</span></a></li>
          <li class="nav-item"><a class="nav-link" href="#map-section"><span class="en-text">Find Hospitals</span><span class="bn-text" style="display: none;">হাসপাতাল খুঁজুন</span></a></li>
          <li class="nav-item"><a class="nav-link" href="#services"><span class="en-text">Services</span><span class="bn-text" style="display: none;">সেবা</span></a></li>
          <li class="nav-item"><a class="nav-link" href="#departments"><span class="en-text">Departments</span><span class="bn-text" style="display: none;">বিভাগ</span></a></li>
          <li class="nav-item"><a class="nav-link" href="#doctors"><span class="en-text">Doctors</span><span class="bn-text" style="display: none;">ডাক্তার</span></a></li>
          <li class="nav-item"><a class="nav-link" href="#testimonials"><span class="en-text">Testimonials</span><span class="bn-text" style="display: none;">প্রশংসাপত্র</span></a></li>
          <li class="nav-item"><a class="nav-link" href="#contact"><span class="en-text">Contact</span><span class="bn-text" style="display: none;">যোগাযোগ</span></a></li>
        </ul>
        <?php if (!empty($_SESSION) && isset($_SESSION['user'])):
          $avatarSrc = null;
          if (!empty($_SESSION['user']['profile_image'])) {
            $p = $_SESSION['user']['profile_image'];
            // If path already looks absolute (http or leading slash), use it; otherwise prefix with site relative path
            if (preg_match('#^https?://#i', $p) || strpos($p, '/') === 0) {
              $avatarSrc = $p;
            } else {
              $avatarSrc = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/' . ltrim($p, '/');
            }
          }
        ?>
          <div class="nav-item dropdown ms-3">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php if (!empty($avatarSrc)): ?>
                <img src="<?php echo htmlspecialchars($avatarSrc); ?>" alt="Profile" class="rounded-circle" width="36" height="36" style="object-fit:cover; margin-right:8px;">
              <?php else: ?>
                <i class="fas fa-user-circle fa-2x me-2"></i>
              <?php endif; ?>
              <span class="en-text"><?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
              <li><a class="dropdown-item" href="profile.php">Edit Profile</a></li>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
          </div>
        <?php else: ?>
          <div class="auth-buttons ms-3">
            <a href="login.php" class="btn btn-outline-primary"><span class="en-text">Login</span><span class="bn-text" style="display: none;">লগইন</span></a>
            <a href="login.php" class="btn btn-primary"><span class="en-text">Register</span><span class="bn-text" style="display: none;">নিবন্ধন</span></a>
          </div>
        <?php endif; ?>
        <button id="themeToggle" class="btn btn-sm btn-light ms-2" title="Toggle dark mode"><i class="fas fa-moon"></i></button>
      </div>
    </nav>
  </div>
</header>

<!-- Search Bar Section -->
<?php if (empty($hide_search)): ?>
  <!-- Search Bar Section -->
  <div class="search-bar-section">
    <div class="container">
      <div class="search-container">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="search-input" placeholder="Search for doctors, services, hospitals...">
        <button class="search-btn"><span class="en-text">Search</span><span class="bn-text" style="display: none;">খুঁজুন</span></button>
      </div>
    </div>
  </div>
<?php endif; ?>