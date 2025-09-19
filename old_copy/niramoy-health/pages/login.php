<?php
require_once '../includes/header.php';
?>
<script>
  const loginErrors = <?php echo isset($_SESSION['login_errors']) ? json_encode($_SESSION['login_errors']) : 'null'; ?>;
  const loginData = <?php echo isset($_SESSION['login_data']) ? json_encode($_SESSION['login_data']) : 'null'; ?>;
</script>
<?php unset($_SESSION['login_errors'], $_SESSION['login_data']); ?>

<div class="auth-page">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        <div class="auth-container">
          <div class="auth-header">
            <h2><?php echo $lang['login']; ?></h2>
            <p><?php echo $lang['login_to_account']; ?></p>
          </div>

          <div class="auth-body">
            <form action="process_login.php" method="post" id="loginForm">
              <div class="form-group">
                <label for="email"><?php echo $lang['email']; ?></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                  <input type="email" class="form-control" id="email" name="email"
                    placeholder="<?php echo $lang['enter_email']; ?>" required>
                </div>
              </div>

              <div class="form-group">
                <label for="password"><?php echo $lang['password']; ?></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input type="password" class="form-control" id="password" name="password"
                    placeholder="<?php echo $lang['enter_password']; ?>" required>
                  <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>

              <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                <label class="form-check-label" for="rememberMe">
                  <?php echo $lang['remember_me']; ?>
                </label>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                  <?php echo $lang['login']; ?>
                </button>
              </div>

              <div class="auth-links">
                <a href="#" class="forgot-password"><?php echo $lang['forgot_password']; ?></a>
              </div>
            </form>

            <div class="auth-divider">
              <span><?php echo $lang['or']; ?></span>
            </div>

            <div class="social-login">
              <button class="btn btn-outline-primary btn-block">
                <i class="fab fa-google"></i> <?php echo $lang['login_with_google']; ?>
              </button>
              <button class="btn btn-outline-primary btn-block">
                <i class="fab fa-facebook-f"></i> <?php echo $lang['login_with_facebook']; ?>
              </button>
            </div>
          </div>

          <div class="auth-footer">
            <p><?php echo $lang['dont_have_account']; ?> <a href="register.php"><?php echo $lang['register']; ?></a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>