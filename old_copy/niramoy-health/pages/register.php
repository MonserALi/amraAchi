<?php
require_once '../includes/header.php';
?>
<script>
  const registerErrors = <?php echo isset($_SESSION['register_errors']) ? json_encode($_SESSION['register_errors']) : 'null'; ?>;
  const registerData = <?php echo isset($_SESSION['register_data']) ? json_encode($_SESSION['register_data']) : 'null'; ?>;
</script>
<?php unset($_SESSION['register_errors'], $_SESSION['register_data']); ?>

<div class="auth-page">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="auth-container">
          <div class="auth-header">
            <h2><?php echo $lang['register']; ?></h2>
            <p><?php echo $lang['create_new_account']; ?></p>
          </div>

          <div class="auth-body">
            <form action="process_register.php" method="post" id="registerForm">
              <div class="form-group">
                <label for="name"><?php echo $lang['full_name']; ?></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                  <input type="text" class="form-control" id="name" name="name"
                    placeholder="<?php echo $lang['enter_full_name']; ?>" required>
                </div>
              </div>

              <div class="form-group">
                <label for="email"><?php echo $lang['email']; ?></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                  <input type="email" class="form-control" id="email" name="email"
                    placeholder="<?php echo $lang['enter_email']; ?>" required>
                </div>
              </div>

              <div class="form-group">
                <label for="phone"><?php echo $lang['phone']; ?></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-phone"></i></span>
                  <input type="tel" class="form-control" id="phone" name="phone"
                    placeholder="<?php echo $lang['enter_phone']; ?>" required>
                </div>
              </div>

              <div class="form-group">
                <label for="userType"><?php echo $lang['register_as']; ?></label>
                <select class="form-select" id="userType" name="userType" required>
                  <option value=""><?php echo $lang['select_user_type']; ?></option>
                  <option value="patient"><?php echo $lang['patient']; ?></option>
                  <option value="doctor"><?php echo $lang['doctor']; ?></option>
                  <option value="nurse"><?php echo $lang['nurse']; ?></option>
                  <option value="driver"><?php echo $lang['driver']; ?></option>
                </select>
              </div>

              <div class="form-group doctor-fields" style="display: none;">
                <label for="bmdcCode"><?php echo $lang['bmdc_code']; ?></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-certificate"></i></span>
                  <input type="text" class="form-control" id="bmdcCode" name="bmdcCode"
                    placeholder="<?php echo $lang['enter_bmdc_code']; ?>">
                </div>
                <small class="form-text text-muted"><?php echo $lang['bmdc_help']; ?></small>
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
                <div class="password-strength mt-2">
                  <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                  </div>
                  <small class="form-text text-muted"><?php echo $lang['password_strength']; ?></small>
                </div>
              </div>

              <div class="form-group">
                <label for="confirmPassword"><?php echo $lang['confirm_password']; ?></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                    placeholder="<?php echo $lang['confirm_password']; ?>" required>
                  <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>

              <div class="form-group">
                <label for="dateOfBirth"><?php echo $lang['date_of_birth']; ?></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                  <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth">
                </div>
              </div>

              <div class="form-group">
                <label for="gender"><?php echo $lang['gender']; ?></label>
                <select class="form-select" id="gender" name="gender">
                  <option value=""><?php echo $lang['select_gender']; ?></option>
                  <option value="male"><?php echo $lang['male']; ?></option>
                  <option value="female"><?php echo $lang['female']; ?></option>
                  <option value="other"><?php echo $lang['other']; ?></option>
                </select>
              </div>

              <div class="form-group">
                <label for="bloodGroup"><?php echo $lang['blood_group']; ?></label>
                <select class="form-select" id="bloodGroup" name="bloodGroup">
                  <option value=""><?php echo $lang['select_blood_group']; ?></option>
                  <option value="A+">A+</option>
                  <option value="A-">A-</option>
                  <option value="B+">B+</option>
                  <option value="B-">B-</option>
                  <option value="AB+">AB+</option>
                  <option value="AB-">AB-</option>
                  <option value="O+">O+</option>
                  <option value="O-">O-</option>
                </select>
              </div>

              <div class="form-group">
                <label for="address"><?php echo $lang['address']; ?></label>
                <textarea class="form-control" id="address" name="address" rows="2"
                  placeholder="<?php echo $lang['enter_address']; ?>"></textarea>
              </div>

              <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="agreeTerms" name="agreeTerms" required>
                <label class="form-check-label" for="agreeTerms">
                  <?php echo $lang['agree_terms']; ?> <a href="#"><?php echo $lang['terms_conditions']; ?></a>
                </label>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                  <?php echo $lang['register']; ?>
                </button>
              </div>
            </form>

            <div class="auth-divider">
              <span><?php echo $lang['or']; ?></span>
            </div>

            <div class="social-login">
              <button class="btn btn-outline-primary btn-block">
                <i class="fab fa-google"></i> <?php echo $lang['register_with_google']; ?>
              </button>
              <button class="btn btn-outline-primary btn-block">
                <i class="fab fa-facebook-f"></i> <?php echo $lang['register_with_facebook']; ?>
              </button>
            </div>
          </div>

          <div class="auth-footer">
            <p><?php echo $lang['already_have_account']; ?> <a href="login.php"><?php echo $lang['login']; ?></a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>