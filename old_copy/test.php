<?php
require_once 'config/app.php';
require_once 'core/Controller.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CSS Test - Niramoy Health</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/main.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/components.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/responsive.css">

  <!-- Dark Mode CSS -->
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/dark-mode.css" id="dark-mode-stylesheet" disabled>
</head>

<body>
  <div class="container mt-5">
    <h1>CSS Test Page</h1>
    <p>If you see this text in blue color, the CSS is loading correctly.</p>

    <div class="alert alert-primary">
      This is a Bootstrap alert. If it's styled, Bootstrap CSS is loading correctly.
    </div>

    <button class="btn btn-primary">Primary Button</button>
    <button class="btn btn-outline-primary">Outline Button</button>

    <div class="mt-4">
      <h3>Dark Mode Toggle</h3>
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="dark-mode-toggle">
        <label class="form-check-label" for="dark-mode-toggle">Dark Mode</label>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JS -->
  <script src="<?php echo BASE_URL; ?>public/js/main.js"></script>
  <script src="<?php echo BASE_URL; ?>public/js/dark-mode.js"></script>

  <script>
    // Set base URL for JavaScript
    const BASE_URL = '<?php echo BASE_URL; ?>';

    // Test JavaScript
    $(document).ready(function() {
      $('#dark-mode-toggle').change(function() {
        if (this.checked) {
          $('body').addClass('dark-mode');
          $('#dark-mode-stylesheet').prop('disabled', false);
        } else {
          $('body').removeClass('dark-mode');
          $('#dark-mode-stylesheet').prop('disabled', true);
        }
      });
    });
  </script>
</body>

</html>