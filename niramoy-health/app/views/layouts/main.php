<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle ?? 'Niramoy Health'; ?></title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>public/images/favicon.ico">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Leaflet CSS for Maps -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/main.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/components.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/responsive.css">

  <!-- Dark Mode CSS (will be loaded conditionally) -->
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/dark-mode.css" id="dark-mode-stylesheet" disabled>

  <?php if (isset($additionalCss)) echo $additionalCss; ?>
</head>

<body class="<?php echo isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] == 'true' ? 'dark-mode' : ''; ?>">
  <?php require_once '../app/views/partials/header.php'; ?>
  <?php require_once '../app/views/partials/navbar.php'; ?>

  <main class="main-content">
    <?php echo $content; ?>
  </main>

  <?php require_once '../app/views/partials/footer.php'; ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Leaflet JS for Maps -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Custom JS -->
  <script src="<?php echo BASE_URL; ?>public/js/main.js"></script>
  <script src="<?php echo BASE_URL; ?>public/js/dark-mode.js"></script>
  <script src="<?php echo BASE_URL; ?>public/js/validation.js"></script>

  <?php if (isset($additionalJs)) echo $additionalJs; ?>

  <script>
    // Set base URL for JavaScript
    const BASE_URL = '<?php echo BASE_URL; ?>';

    // Initialize dark mode based on cookie
    document.addEventListener('DOMContentLoaded', function() {
      const darkModeCookie = getCookie('dark_mode');
      if (darkModeCookie === 'true') {
        document.body.classList.add('dark-mode');
        document.getElementById('dark-mode-stylesheet').disabled = false;
        document.getElementById('dark-mode-toggle').checked = true;
      }
    });
  </script>
</body>

</html>