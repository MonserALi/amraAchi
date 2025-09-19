<?php
require_once __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $lang['site_name']; ?> - <?php echo $lang['site_tagline']; ?></title>

  <!-- Favicon -->
  <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

  <!-- CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/responsive.css">

  <!-- Theme CSS -->
  <?php if ($theme_mode === 'dark'): ?>
    <link rel="stylesheet" href="assets/css/dark-mode.css" id="theme-css">
  <?php endif; ?>

  <!-- RTL Support for Bangla -->
  <?php if ($lang_code === 'bn'): ?>
    <link rel="stylesheet" href="assets/css/rtl.css">
  <?php endif; ?>
</head>

<body class="<?php echo $theme_mode; ?>-mode">
  <!-- Skip to main content for accessibility -->
  <a href="#main-content" class="skip-link">Skip to main content</a>

  <!-- SOS Button -->
  <div class="sos-button">
    <button id="sos-btn" class="btn btn-danger rounded-circle">
      <i class="fas fa-phone-alt"></i>
      <span>SOS</span>
    </button>
  </div>

  <!-- Header -->
  <header class="main-header">
    <div class="container">
      <div class="header-top">
        <div class="row align-items-center">
          <div class="col-md-6">
            <div class="contact-info">
              <span><i class="fas fa-phone"></i> +880 1234 567890</span>
              <span><i class="fas fa-envelope"></i> info@niramoy.com</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="user-actions justify-content-end d-flex align-items-center gap-2">
              <button id="language-toggle-btn" class="btn btn-sm btn-outline-secondary" style="width:36px;height:36px;padding:0;">
                <?php if ($lang_code === 'en'): ?>
                  <span title="বাংলা"><i class="fas fa-language"></i></span>
                <?php else: ?>
                  <span title="English"><i class="fas fa-language"></i></span>
                <?php endif; ?>
              </button>
              <button id="theme-toggle-btn" class="btn btn-sm btn-outline-secondary" style="width:36px;height:36px;padding:0;">
                <?php if ($theme_mode === 'light'): ?>
                  <i class="fas fa-moon"></i>
                <?php else: ?>
                  <i class="fas fa-sun"></i>
                <?php endif; ?>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-main sticky-top">
      <div class="container">
        <a class="navbar-brand" href="index.php">
          <img src="assets/images/logo.png" alt="<?php echo $lang['site_name']; ?>" class="logo">
          <span><?php echo $lang['site_name']; ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
          <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item">
              <a class="nav-link active" href="index.php"><?php echo $lang['home']; ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><?php echo $lang['about']; ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="pages/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo $lang['services']; ?>
              </a>
              <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                <li><a class="dropdown-item" href="#emergency"><?php echo $lang['emergency']; ?></a></li>
                <li><a class="dropdown-item" href="#doctors"><?php echo $lang['doctors']; ?></a></li>
                <li><a class="dropdown-item" href="#hospitals"><?php echo $lang['hospitals']; ?></a></li>
                <li><a class="dropdown-item" href="#daycare"><?php echo $lang['daycare_services']; ?></a></li>
                <li><a class="dropdown-item" href="#ambulance"><?php echo $lang['ambulance_service']; ?></a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="pages/blog.php"><?php echo $lang['blog']; ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><?php echo $lang['contact']; ?></a>
            </li>
            <?php if (isset($_SESSION['user'])): ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <img src="assets/images/avatar-default.png" alt="Profile" class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover;">
                  <span class="d-none d-md-inline">Profile</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                  <li><a class="dropdown-item" href="pages/profile.php"><i class="fas fa-user"></i> Edit Profile</a></li>
                  <li><a class="dropdown-item" href="pages/logout.php"><i class="fas fa-sign-out-alt"></i> <?php echo $lang['logout']; ?></a></li>
                </ul>
              </li>
            <?php else: ?>
              <li class="nav-item">
                <a href="pages/login.php" class="btn btn-sm btn-outline-primary mx-1"><i class="fas fa-sign-in-alt"></i> <span class="d-none d-md-inline">Login</span></a>
              </li>
              <li class="nav-item">
                <a href="pages/register.php" class="btn btn-sm btn-primary mx-1"><i class="fas fa-user-plus"></i> <span class="d-none d-md-inline">Register</span></a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <!-- Main Content -->
  <main id="main-content">