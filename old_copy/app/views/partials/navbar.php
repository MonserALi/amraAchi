<!-- Main Header - Sticky -->
<header class="main-header">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
        <img src="<?php echo BASE_URL; ?>public/images/logo.png" alt="Niramoy Health" height="40">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link <?php echo $currentPage == 'home' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>">
              <span class="en-text">Home</span><span class="bn-text" style="display: none;">হোম</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $currentPage == 'hospitals' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>hospitals">
              <span class="en-text">Find Hospitals</span><span class="bn-text" style="display: none;">হাসপাতাল খুঁজুন</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $currentPage == 'services' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>services">
              <span class="en-text">Services</span><span class="bn-text" style="display: none;">সেবা</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $currentPage == 'doctors' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>doctors">
              <span class="en-text">Doctors</span><span class="bn-text" style="display: none;">ডাক্তার</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $currentPage == 'about' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>about">
              <span class="en-text">About Us</span><span class="bn-text" style="display: none;">আমাদের সম্পর্কে</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $currentPage == 'contact' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>contact">
              <span class="en-text">Contact</span><span class="bn-text" style="display: none;">যোগাযোগ</span>
            </a>
          </li>
        </ul>

        <?php if (Auth::isLoggedIn()): ?>
          <div class="user-menu ms-3">
            <div class="dropdown">
              <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle me-1"></i> <?php echo Session::get('user_name'); ?>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>dashboard"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>profile"><i class="fas fa-user me-2"></i> Profile</a></li>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>settings"><i class="fas fa-cog me-2"></i> Settings</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
              </ul>
            </div>
          </div>
        <?php else: ?>
          <div class="auth-buttons ms-3">
            <a href="<?php echo BASE_URL; ?>login" class="btn btn-outline-primary">
              <span class="en-text">Login</span><span class="bn-text" style="display: none;">লগইন</span>
            </a>
            <a href="<?php echo BASE_URL; ?>register" class="btn btn-primary">
              <span class="en-text">Register</span><span class="bn-text" style="display: none;">নিবন্ধন</span>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </nav>
  </div>
</header>

<!-- Search Bar Section - Sticky -->
<div class="search-bar-section">
  <div class="container">
    <div class="search-container">
      <i class="fas fa-search search-icon"></i>
      <input type="text" class="search-input" placeholder="Search for doctors, services, hospitals...">
      <button class="search-btn">
        <span class="en-text">Search</span><span class="bn-text" style="display: none;">খুঁজুন</span>
      </button>
    </div>
  </div>
</div>

<!-- Emergency Buttons -->
<div class="emergency-buttons">
  <a href="<?php echo BASE_URL; ?>sos" class="sos-button">
    <i class="fas fa-ambulance"></i>
    <span class="emergency-text en-text">SOS</span>
    <span class="emergency-text bn-text" style="display: none;">এসওএস</span>
  </a>
  <a href="<?php echo BASE_URL; ?>epidemic-alert" class="epidemic-button">
    <i class="fas fa-virus"></i>
    <span class="emergency-text en-text">Alert</span>
    <span class="emergency-text bn-text" style="display: none;">সতর্কতা</span>
  </a>
</div>