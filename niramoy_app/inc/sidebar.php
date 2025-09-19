<?php
// sidebar
?>

<aside class="sidebar" id="sidebar" aria-label="Main navigation">
  <div class="sidebar-header">
    <div class="sidebar-logo">
      <i class="fas fa-heartbeat"></i>
      <span class="lang-text en">AmraAchi</span>
      <span class="lang-text bn">আমরাআছি</span>
    </div>
    <button class="close-sidebar" id="closeSidebar" aria-label="Close navigation">
      <i class="fas fa-times"></i>
    </button>
  </div>
  <nav>
    <ul class="sidebar-menu">
      <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> <span class="lang-text en">Dashboard</span><span class="lang-text bn">ড্যাশবোর্ড</span></a></li>
      <li><a href="tasks.php"><i class="fas fa-tasks"></i> <span class="lang-text en">Tasks</span><span class="lang-text bn">কাজ</span></a></li>
      <li><a href="patients.php"><i class="fas fa-users"></i> <span class="lang-text en">Patients</span><span class="lang-text bn">রোগী</span></a></li>
      <li><a href="medications.php"><i class="fas fa-pills"></i> <span class="lang-text en">Medications</span><span class="lang-text bn">ওষুধ</span></a></li>
      <li><a href="vitals.php"><i class="fas fa-heartbeat"></i> <span class="lang-text en">Vital Signs</span><span class="lang-text bn">প্রাণসংকেত</span></a></li>
      <li><a href="schedule.php"><i class="fas fa-calendar-alt"></i> <span class="lang-text en">Schedule</span><span class="lang-text bn">সময়সূচী</span></a></li>
      <li><a href="messages.php"><i class="fas fa-comments"></i> <span class="lang-text en">Messages</span><span class="lang-text bn">বার্তা</span></a></li>
      <li><a href="chat.php"><i class="fas fa-comments-dollar"></i> <span class="lang-text en">Chat</span><span class="lang-text bn">চ্যাট</span></a></li>
      <li><a href="reports.php"><i class="fas fa-file-medical-alt"></i> <span class="lang-text en">Reports</span><span class="lang-text bn">রিপোর্ট</span></a></li>
      <li><a href="#"><i class="fas fa-user"></i> <span class="lang-text en">Profile</span><span class="lang-text bn">প্রোফাইল</span></a></li>
      <li><a href="#"><i class="fas fa-cog"></i> <span class="lang-text en">Settings</span><span class="lang-text bn">সেটিংস</span></a></li>
      <li>
        <hr class="dropdown-divider">
      </li>
      <!-- hide navigation removed to always show full navigation -->
    </ul>
  </nav>
  <div class="sidebar-footer">
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span class="lang-text en">Logout</span><span class="lang-text bn">লগআউট</span></a>
  </div>
</aside>
<!-- Overlay -->
<div class="overlay" id="overlay" aria-label="Sidebar overlay"></div>
<!-- Navigation hide/show removed globally; navigation always visible -->