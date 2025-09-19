    </main>

    <!-- Footer -->
    <footer class="main-footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-md-6">
            <div class="footer-about">
              <a href="index.php" class="footer-logo">
                <img src="assets/images/logo.png" alt="<?php echo $lang['site_name']; ?>">
                <span><?php echo $lang['site_name']; ?></span>
              </a>
              <p><?php echo $lang['welcome_message']; ?></p>
              <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-md-6">
            <div class="footer-links">
              <h3><?php echo $lang['quick_links']; ?></h3>
              <ul>
                <li><a href="#"><?php echo $lang['about_us']; ?></a></li>
                <li><a href="#"><?php echo $lang['services']; ?></a></li>
                <li><a href="#"><?php echo $lang['doctors']; ?></a></li>
                <li><a href="#"><?php echo $lang['hospitals']; ?></a></li>
                <li><a href="#"><?php echo $lang['contact_us']; ?></a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="footer-links">
              <h3><?php echo $lang['our_services']; ?></h3>
              <ul>
                <li><a href="#emergency"><?php echo $lang['emergency']; ?></a></li>
                <li><a href="#doctors"><?php echo $lang['doctor_consultation']; ?></a></li>
                <li><a href="#daycare"><?php echo $lang['daycare_services']; ?></a></li>
                <li><a href="#ambulance"><?php echo $lang['ambulance_service']; ?></a></li>
                <li><a href="#reports"><?php echo $lang['health_reports']; ?></a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="footer-contact">
              <h3><?php echo $lang['contact_us']; ?></h3>
              <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <p>123 Healthcare Avenue, Dhaka, Bangladesh</p>
              </div>
              <div class="contact-item">
                <i class="fas fa-phone"></i>
                <p>+880 1234 567890</p>
              </div>
              <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <p>info@niramoy.com</p>
              </div>
              <div class="app-download">
                <h4><?php echo $lang['download_app']; ?></h4>
                <div class="app-buttons">
                  <a href="#"><img src="assets/images/google-play.png" alt="Google Play"></a>
                  <a href="#"><img src="assets/images/app-store.png" alt="App Store"></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="copyright">
          <p>&copy; <?php echo date('Y'); ?> <?php echo $lang['site_name']; ?>. <?php echo $lang['all_rights_reserved']; ?>.</p>
        </div>
      </div>
    </footer>

    <!-- Back to top button -->
    <button id="backToTop" class="btn btn-primary"><i class="fas fa-arrow-up"></i></button>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/language-switcher.js"></script>
    <script src="assets/js/theme-toggle.js"></script>
    <script src="assets/js/map.js"></script>
    <script src="assets/js/sos-button.js"></script>
    </body>

    </html>