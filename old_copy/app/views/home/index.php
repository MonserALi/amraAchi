<div class="hero-section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="hero-content">
          <h1><span class="en-text">Your Complete Digital Healthcare Solution</span><span class="bn-text" style="display: none;">আপনার সম্পূর্ণ ডিজিটাল স্বাস্থ্যসেবা সমাধান</span></h1>
          <p><span class="en-text">Niramoy Health connects patients, doctors, and healthcare services in one integrated platform for better health outcomes.</span><span class="bn-text" style="display: none;">নিরাময় হেলথ রোগী, ডাক্তার এবং স্বাস্থ্যসেবা সেবাকে একটি সমন্বিত প্ল্যাটফর্মে সংযুক্ত করে উন্নত স্বাস্থ্য ফলাফলের জন্য।</span></p>
          <div class="d-flex flex-wrap">
            <a href="#appointment" class="btn btn-primary-custom hero-btn"><span class="en-text">Book Appointment</span><span class="bn-text" style="display: none;">অ্যাপয়েন্টমেন্ট বুক করুন</span></a>
            <a href="#map-section" class="btn btn-outline-custom hero-btn"><span class="en-text">Find Hospitals</span><span class="bn-text" style="display: none;">হাসপাতাল খুঁজুন</span></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Epidemic Alert Banner on Homepage -->
<div class="epidemic-banner" id="epidemicBanner">
  <div class="container">
    <div class="epidemic-content">
      <div class="epidemic-info">
        <div class="epidemic-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="epidemic-text">
          <h3><span class="en-text">COVID-19 Alert</span><span class="bn-text" style="display: none;">কোভিড-১৯ সতর্কতা</span></h3>
          <p><span class="en-text">Cases are rising. Protect yourself and others.</span><span class="bn-text" style="display: none;">কেস বাড়ছে। নিজেকে এবং অন্যদের রক্ষা করুন।</span></p>
        </div>
      </div>
      <div class="epidemic-actions">
        <button class="epidemic-learn-btn" onclick="showEpidemicDetails()"><span class="en-text">Learn More</span><span class="bn-text" style="display: none;">আরও জানুন</span></button>
        <button class="close-banner" id="closeBanner">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Map Section - Moved Up -->
<section id="map-section" class="map-section">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="section-title"><span class="en-text">Find Hospitals Near You</span><span class="bn-text" style="display: none;">আপনার কাছাকাছি হাসপাতাল খুঁজুন</span></h2>
      <p class="section-subtitle"><span class="en-text">Locate the nearest hospitals and healthcare facilities with our interactive map</span><span class="bn-text" style="display: none;">আমাদের ইন্টারেক্টিভ মানচিত্রের সাথে নিকটস্থ হাসপাতাল এবং স্বাস্থ্যসেবা সুবিধা খুঁজুন</span></p>
    </div>
    <div class="row">
      <div class="col-lg-8 mb-4">
        <div id="map"></div>
      </div>
      <div class="col-lg-4">
        <div class="hospital-list" id="hospital-list">
          <!-- Hospital cards will be dynamically inserted here -->
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Epidemic Alert Section -->
<section id="epidemic-alert" class="epidemic-alert-section">
  <button class="close-epidemic" id="closeEpidemic">
    <i class="fas fa-times"></i>
  </button>
  <div class="container">
    <div class="epidemic-alert-content">
      <h2 class="epidemic-alert-title"><span class="en-text">COVID-19 Health Alert</span><span class="bn-text" style="display: none;">কোভিড-১৯ স্বাস্থ্য সতর্কতা</span></h2>
      <p class="epidemic-alert-subtitle"><span class="en-text">Important information and guidelines to protect yourself and others</span><span class="bn-text" style="display: none;">নিজেকে এবং অন্যদের রক্ষা করার জন্য গুরুত্বপূর্ণ তথ্য এবং নির্দেশিকা</span></p>

      <div class="epidemic-steps">
        <div class="row">
          <div class="col-md-6">
            <div class="epidemic-step">
              <h3><i class="fas fa-hands-wash"></i> <span class="en-text">Wash Your Hands</span><span class="bn-text" style="display: none;">আপনার হাত ধুয়ে ফেলুন</span></h3>
              <p><span class="en-text">Wash your hands frequently with soap and water for at least 20 seconds or use hand sanitizer with at least 60% alcohol.</span><span class="bn-text" style="display: none;">সাবান এবং পানি দিয়ে কমপক্ষে ২০ সেকেন্ডের জন্য ঘন ঘন আপনার হাত ধুয়ে ফেলুন বা কমপক্ষে ৬০% অ্যালকোহল সহ হ্যান্ড স্যানিটাইজার ব্যবহার করুন।</span></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="epidemic-step">
              <h3><i class="fas fa-head-side-mask"></i> <span class="en-text">Wear a Mask</span><span class="bn-text" style="display: none;">মাস্ক পরুন</span></h3>
              <p><span class="en-text">Wear a mask that covers your nose and mouth in public settings, especially when social distancing is difficult.</span><span class="bn-text" style="display: none;">পাবলিক সেটিংসে আপনার নাক এবং মুখ কভার করে একটি মাস্ক পরুন, বিশেষ করে যখন সামাজিক দূরত্ব কঠিন।</span></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="epidemic-step">
              <h3><i class="fas fa-people-arrows"></i> <span class="en-text">Social Distance</span><span class="bn-text" style="display: none;">সামাজিক দূরত্ব</span></h3>
              <p><span class="en-text">Maintain at least 6 feet (about 2 arm lengths) distance from others who are not from your household.</span><span class="bn-text" style="display: none;">আপনার পরিবার থেকে নয় এমন অন্যদের থেকে কমপক্ষে ৬ ফুট (প্রায় ২ হাতের দৈর্ঘ্য) দূরত্ব বজায় রাখুন।</span></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="epidemic-step">
              <h3><i class="fas fa-syringe"></i> <span class="en-text">Get Vaccinated</span><span class="bn-text" style="display: none;">টিকা নিন</span></h3>
              <p><span class="en-text">COVID-19 vaccines are effective at preventing severe illness, hospitalizations, and death. Get vaccinated as soon as you can.</span><span class="bn-text" style="display: none;">কোভিড-১৯ টিকা গুরুতর অসুস্থতা, হাসপাতালে ভর্তি এবং মৃত্যু প্রতিরোধে কার্যকর। যত তাড়াতাড়ি সম্ভব টিকা নিন।</span></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Services Section -->
<section id="services" class="services-section">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="section-title"><span class="en-text">Our Services</span><span class="bn-text" style="display: none;">আমাদের সেবা</span></h2>
      <p class="section-subtitle"><span class="en-text">We offer a comprehensive range of healthcare services to meet all your medical needs</span><span class="bn-text" style="display: none;">আমরা আপনার সমস্ত চিকিৎসা প্রয়োজন মেটাতে স্বাস্থ্যসেবা পরিষেবার একটি বিস্তৃত পরিসর অফার করি</span></p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-ambulance"></i>
          </div>
          <h3><span class="en-text">Emergency Services</span><span class="bn-text" style="display: none;">জরুরি সেবা</span></h3>
          <p><span class="en-text">24/7 emergency care with rapid response and advanced life support systems.</span><span class="bn-text" style="display: none;">দ্রুত প্রতিক্রিয়া এবং উন্নত লাইফ সাপোর্ট সিস্টেম সহ ২৪/৭ জরুরি যত্ন।</span></p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-calendar-check"></i>
          </div>
          <h3><span class="en-text">Online Appointments</span><span class="bn-text" style="display: none;">অনলাইন অ্যাপয়েন্টমেন্ট</span></h3>
          <p><span class="en-text">Book appointments with certified doctors easily and manage your schedule.</span><span class="bn-text" style="display: none;">প্রত্যয়িত ডাক্তারদের সাথে সহজেই অ্যাপয়েন্টমেন্ট বুক করুন এবং আপনার সময়সূচী পরিচালনা করুন।</span></p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-file-medical"></i>
          </div>
          <h3><span class="en-text">Health Records</span><span class="bn-text" style="display: none;">স্বাস্থ্য রেকর্ড</span></h3>
          <p><span class="en-text">Securely store and access your medical history, prescriptions, and test reports.</span><span class="bn-text" style="display: none;">নিরাপদে আপনার চিকিৎসা ইতিহাস, প্রেসক্রিপশন এবং পরীক্ষার রিপোর্ট সংরক্ষণ এবং অ্যাক্সেস করুন।</span></p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-user-nurse"></i>
          </div>
          <h3><span class="en-text">Home Care Services</span><span class="bn-text" style="display: none;">হোম কেয়ার সার্ভিস</span></h3>
          <p><span class="en-text">Book experienced nurses and caregivers for home medical assistance.</span><span class="bn-text" style="display: none;">হোম মেডিকেল সহায়তার জন্য অভিজ্ঞ নার্স এবং কেয়ারগিভার বুক করুন।</span></p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-pills"></i>
          </div>
          <h3><span class="en-text">E-Prescriptions</span><span class="bn-text" style="display: none;">ই-প্রেসক্রিপশন</span></h3>
          <p><span class="en-text">Digital prescriptions that make medication management simple and error-free.</span><span class="bn-text" style="display: none;">ডিজিটাল প্রেসক্রিপশন যা ওষুধ ব্যবস্থাপনাকে সহজ এবং ত্রুটিমুক্ত করে।</span></p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-virus"></i>
          </div>
          <h3><span class="en-text">Health Alerts</span><span class="bn-text" style="display: none;">স্বাস্থ্য সতর্কতা</span></h3>
          <p><span class="en-text">Get timely notifications about disease outbreaks and preventive health tips.</span><span class="bn-text" style="display: none;">রোগের প্রাদুর্ভাব এবং প্রতিরোধমূলক স্বাস্থ্য টিপস সম্পর্কে সময়োপযোগী বিজ্ঞপ্তি পান।</span></p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-stethoscope"></i>
          </div>
          <h3><span class="en-text">Doctor Search</span><span class="bn-text" style="display: none;">ডাক্তার খোঁজ</span></h3>
          <p><span class="en-text">Find the right specialist by location, expertise, and patient reviews.</span><span class="bn-text" style="display: none;">অবস্থান, দক্ষতা এবং রোগীর পর্যালোচনা দ্বারা সঠিক বিশেষজ্ঞ খুঁজুন।</span></p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-mobile-alt"></i>
          </div>
          <h3><span class="en-text">Mobile App</span><span class="bn-text" style="display: none;">মোবাইল অ্যাপ</span></h3>
          <p><span class="en-text">Access all features on-the-go with our Android application.</span><span class="bn-text" style="display: none;">আমাদের অ্যান্ড্রয়েড অ্যাপ্লিকেশন দিয়ে চলতি পথে সমস্ত বৈশিষ্ট্যগুলি অ্যাক্সেস করুন।</span></p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Stats Section with Flip Cards -->
<section class="stats-section">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-5 fw-bold">
        <span class="en-text">Trusted by Thousands</span>
        <span class="bn-text" style="display: none;">হাজারো মানুষের আস্থায়</span>
      </h2>
      <p class="lead">
        <span class="en-text">Join our growing community of satisfied patients</span>
        <span class="bn-text" style="display: none;">আমাদের ক্রমবর্ধমান সন্তুষ্ট রোগী সম্প্রদায়ে যোগ দিন</span>
      </p>
    </div>
    <div class="row g-4">
      <div class="col-md-3 col-sm-6">
        <div class="flip-card">
          <div class="flip-card-inner">
            <div class="flip-card-front">
              <div class="stat-icon">
                <i class="fas fa-user-md"></i>
              </div>
              <h3>1700+</h3>
              <p>
                <span class="en-text">BMDC Verified Doctors</span>
                <span class="bn-text" style="display: none;">বিএমডিসি প্রত্যয়িত ডাক্তার</span>
              </p>
            </div>
            <div class="flip-card-back">
              <h3>
                <span class="en-text">Expert Medical Team</span>
                <span class="bn-text" style="display: none;">বিশেষজ্ঞ মেডিকেল টিম</span>
              </h3>
              <p>
                <span class="en-text">All our doctors are certified by BMDC and have years of experience</span>
                <span class="bn-text" style="display: none;">আমাদের সকল ডাক্তার বিএমডিসি দ্বারা প্রত্যয়িত এবং বছরের অভিজ্ঞতা সম্পন্ন</span>
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="flip-card">
          <div class="flip-card-inner">
            <div class="flip-card-front">
              <div class="stat-icon">
                <i class="fas fa-clock"></i>
              </div>
              <h3>10 Minutes</h3>
              <p>
                <span class="en-text">Average Waiting Time</span>
                <span class="bn-text" style="display: none;">গড় অপেক্ষা সময়</span>
              </p>
            </div>
            <div class="flip-card-back">
              <h3>
                <span class="en-text">Quick Consultations</span>
                <span class="bn-text" style="display: none;">দ্রুত পরামর্শ</span>
              </h3>
              <p>
                <span class="en-text">Get connected with a doctor in just 10 minutes on average</span>
                <span class="bn-text" style="display: none;">গড়ে মাত্র ১০ মিনিটের মধ্যে একজন ডাক্তারের সাথে যুক্ত হন</span>
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="flip-card">
          <div class="flip-card-inner">
            <div class="flip-card-front">
              <div class="stat-icon">
                <i class="fas fa-users"></i>
              </div>
              <h3>700K+</h3>
              <p>
                <span class="en-text">Trusted Users</span>
                <span class="bn-text" style="display: none;">বিশ্বস্ত ব্যবহারকারী</span>
              </p>
            </div>
            <div class="flip-card-back">
              <h3>
                <span class="en-text">Large Community</span>
                <span class="bn-text" style="display: none;">বৃহৎ সম্প্রদায়</span>
              </h3>
              <p>
                <span class="en-text">Over 700,000 people trust us with their healthcare needs</span>
                <span class="bn-text" style="display: none;">৭ লাখেরও বেশি মানুষ তাদের স্বাস্থ্যসেবা প্রয়োজনে আমাদের উপর আস্থা রাখেন</span>
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="flip-card">
          <div class="flip-card-inner">
            <div class="flip-card-front">
              <div class="stat-icon">
                <i class="fas fa-star"></i>
              </div>
              <h3>95%</h3>
              <p>
                <span class="en-text">5-Star Ratings</span>
                <span class="bn-text" style="display: none;">৫-তারকা রেটিং</span>
              </p>
            </div>
            <div class="flip-card-back">
              <h3>
                <span class="en-text">High Satisfaction</span>
                <span class="bn-text" style="display: none;">উচ্চ সন্তুষ্টি</span>
              </h3>
              <p>
                <span class="en-text">95% of our users rate their experience with 5 stars</span>
                <span class="bn-text" style="display: none;">আমাদের ৯৫% ব্যবহারকারী তাদের অভিজ্ঞতাকে ৫ তারকা রেটিং দিয়েছেন</span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Departments Section with Scroller -->
<section id="departments" class="departments-section">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="section-title"><span class="en-text">Our Departments</span><span class="bn-text" style="display: none;">আমাদের বিভাগ</span></h2>
      <p class="section-subtitle"><span class="en-text">We have specialized departments to provide comprehensive healthcare services</span><span class="bn-text" style="display: none;">আমাদের বিশেষায়িত বিভাগ রয়েছে যা ব্যাপক স্বাস্থ্যসেবা প্রদান করে</span></p>
    </div>
    <div class="departments-container">
      <div class="departments-scroller" id="departmentsScroller">
        <div class="department-card">
          <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Cardiology" class="department-img">
          <div class="department-content">
            <h3><span class="en-text">Cardiology</span><span class="bn-text" style="display: none;">কার্ডিওলজি</span></h3>
            <p><span class="en-text">Our cardiology department provides comprehensive care for heart conditions with state-of-the-art facilities.</span><span class="bn-text" style="display: none;">আমাদের কার্ডিওলজি বিভাগ হৃদরোগের অবস্থার জন্য সর্বশেষ সুবিধাসহ ব্যাপক যত্ন প্রদান করে।</span></p>
            <button class="department-btn"><span class="en-text">Learn More</span><span class="bn-text" style="display: none;">আরও জানুন</span></button>
          </div>
        </div>
        <div class="department-card">
          <img src="https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Neurology" class="department-img">
          <div class="department-content">
            <h3><span class="en-text">Neurology</span><span class="bn-text" style="display: none;">নিউরোলজি</span></h3>
            <p><span class="en-text">Expert diagnosis and treatment for disorders of the brain, spinal cord, and nervous system.</span><span class="bn-text" style="display: none;">মস্তিষ্ক, স্নায়ুতন্ত্র এবং স্নায়ুতন্ত্রের ব্যাধির জন্য বিশেষজ্ঞ রোগ নির্ণয় এবং চিকিৎসা।</span></p>
            <button class="department-btn"><span class="en-text">Learn More</span><span class="bn-text" style="display: none;">আরও জানুন</span></button>
          </div>
        </div>
        <div class="department-card">
          <img src="https://images.unsplash.com/photo-1579684385127-acec1938f2d7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Pediatrics" class="department-img">
          <div class="department-content">
            <h3><span class="en-text">Pediatrics</span><span class="bn-text" style="display: none;">পেডিয়াট্রিক্স</span></h3>
            <p><span class="en-text">Comprehensive healthcare services for infants, children, and adolescents with compassionate care.</span><span class="bn-text" style="display: none;">সহানুভূতিশীল যত্নের সাথে শিশু, শিশু এবং কিশোর-কিশোরীদের জন্য ব্যাপক স্বাস্থ্যসেবা পরিষেবা।</span></p>
            <button class="department-btn"><span class="en-text">Learn More</span><span class="bn-text" style="display: none;">আরও জানুন</span></button>
          </div>
        </div>
        <div class="department-card">
          <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Orthopedics" class="department-img">
          <div class="department-content">
            <h3><span class="en-text">Orthopedics</span><span class="bn-text" style="display: none;">অর্থোপেডিক্স</span></h3>
            <p><span class="en-text">Specialized care for bones, joints, ligaments, and muscles with advanced surgical techniques.</span><span class="bn-text" style="display: none;">উন্নত সার্জিক্যাল কৌশল সহ হাড়, জয়েন্ট, লিগামেন্ট এবং পেশীর জন্য বিশেষায়িত যত্ন।</span></p>
            <button class="department-btn"><span class="en-text">Learn More</span><span class="bn-text" style="display: none;">আরও জানুন</span></button>
          </div>
        </div>
        <div class="department-card">
          <img src="https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Gynecology" class="department-img">
          <div class="department-content">
            <h3><span class="en-text">Gynecology</span><span class="bn-text" style="display: none;">গাইনোকোলজি</span></h3>
            <p><span class="en-text">Complete women's healthcare services from routine check-ups to specialized treatments.</span><span class="bn-text" style="display: none;">রুটিন চেক-আপ থেকে শুরু করে বিশেষায়িত চিকিৎসা পর্যন্ত সম্পূর্ণ মহিলা স্বাস্থ্যসেবা পরিষেবা।</span></p>
            <button class="department-btn"><span class="en-text">Learn More</span><span class="bn-text" style="display: none;">আরও জানুন</span></button>
          </div>
        </div>
        <div class="department-card">
          <img src="https://images.unsplash.com/photo-1579684385127-acec1938f2d7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Radiology" class="department-img">
          <div class="department-content">
            <h3><span class="en-text">Radiology</span><span class="bn-text" style="display: none;">রেডিওলজি</span></h3>
            <p><span class="en-text">Advanced imaging services for accurate diagnosis with cutting-edge technology.</span><span class="bn-text" style="display: none;">কাটিং-এজ প্রযুক্তি সহ নির্ভুল রোগ নির্ণয়ের জন্য উন্নত ইমেজিং পরিষেবা।</span></p>
            <button class="department-btn"><span class="en-text">Learn More</span><span class="bn-text" style="display: none;">আরও জানুন</span></button>
          </div>
        </div>
      </div>
    </div>
    <div class="scroll-controls">
      <button class="scroll-btn" id="scrollLeft"><i class="fas fa-chevron-left"></i></button>
      <button class="scroll-btn" id="scrollRight"><i class="fas fa-chevron-right"></i></button>
    </div>
  </div>
</section>

<!-- Doctors Section -->
<section id="doctors" class="doctors-section">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="section-title"><span class="en-text">Our Doctors</span><span class="bn-text" style="display: none;">আমাদের ডাক্তার</span></h2>
      <p class="section-subtitle"><span class="en-text">Meet our team of experienced and compassionate healthcare professionals</span><span class="bn-text" style="display: none;">আমাদের অভিজ্ঞ এবং সহানুভূতিশীল স্বাস্থ্যসেবা পেশাদারদের দলের সাথে পরিচিত হন</span></p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="doctor-card">
          <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Doctor" class="doctor-img">
          <div class="doctor-content">
            <h4 class="doctor-name"><span class="en-text">Dr. Ahmed Khan</span><span class="bn-text" style="display: none;">ডাঃ আহমেদ খান</span></h4>
            <p class="doctor-specialty"><span class="en-text">Cardiologist</span><span class="bn-text" style="display: none;">হৃদরোগ বিশেষজ্ঞ</span></p>
            <p class="doctor-bio"><span class="en-text">15 years of experience in interventional cardiology with numerous publications.</span><span class="bn-text" style="display: none;">হস্তক্ষেপমূলক কার্ডিওলজিতে ১৫ বছরের অভিজ্ঞতা এবং অসংখ্য প্রকাশনা।</span></p>
            <div class="doctor-social">
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <button class="doctor-btn"><span class="en-text">View Profile</span><span class="bn-text" style="display: none;">প্রোফাইল দেখুন</span></button>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="doctor-card">
          <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Doctor" class="doctor-img">
          <div class="doctor-content">
            <h4 class="doctor-name"><span class="en-text">Dr. Fatima Rahman</span><span class="bn-text" style="display: none;">ডাঃ ফাতেমা রহমান</span></h4>
            <p class="doctor-specialty"><span class="en-text">Neurologist</span><span class="bn-text" style="display: none;">নিউরোলজিস্ট</span></p>
            <p class="doctor-bio"><span class="en-text">Specialized in stroke treatment and neurodegenerative disorders.</span><span class="bn-text" style="display: none;">স্ট্রোক চিকিৎসা এবং নিউরোডিজেনারেটিভ ডিসঅর্ডারে বিশেষজ্ঞ।</span></p>
            <div class="doctor-social">
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <button class="doctor-btn"><span class="en-text">View Profile</span><span class="bn-text" style="display: none;">প্রোফাইল দেখুন</span></button>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="doctor-card">
          <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Doctor" class="doctor-img">
          <div class="doctor-content">
            <h4 class="doctor-name"><span class="en-text">Dr. Mohammad Ali</span><span class="bn-text" style="display: none;">ডাঃ মোহাম্মদ আলী</span></h4>
            <p class="doctor-specialty"><span class="en-text">Pediatrician</span><span class="bn-text" style="display: none;">শিশুরোগ বিশেষজ্ঞ</span></p>
            <p class="doctor-bio"><span class="en-text">Dedicated to providing comprehensive care for children of all ages.</span><span class="bn-text" style="display: none;">সব বয়সী শিশুদের জন্য ব্যাপক যত্ন প্রদানে নিবেদিত।</span></p>
            <div class="doctor-social">
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <button class="doctor-btn"><span class="en-text">View Profile</span><span class="bn-text" style="display: none;">প্রোফাইল দেখুন</span></button>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="doctor-card">
          <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Doctor" class="doctor-img">
          <div class="doctor-content">
            <h4 class="doctor-name"><span class="en-text">Dr. Nusrat Jahan</span><span class="bn-text" style="display: none;">ডাঃ নুসরাত জাহান</span></h4>
            <p class="doctor-specialty"><span class="en-text">Gynecologist</span><span class="bn-text" style="display: none;">গাইনোকোলজিস্ট</span></p>
            <p class="doctor-bio"><span class="en-text">Expert in high-risk pregnancies and minimally invasive surgeries.</span><span class="bn-text" style="display: none;">ঝুঁকিপূর্ণ গর্ভাবস্থা এবং ন্যূনতম আক্রমণাত্মক সার্জারিতে বিশেষজ্ঞ।</span></p>
            <div class="doctor-social">
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <button class="doctor-btn"><span class="en-text">View Profile</span><span class="bn-text" style="display: none;">প্রোফাইল দেখুন</span></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Appointment Section -->
<section id="appointment" class="appointment-section">
  <div class="container">
    <div class="appointment-content">
      <h2><span class="en-text">Need an Appointment?</span><span class="bn-text" style="display: none;">অ্যাপয়েন্টমেন্ট প্রয়োজন?</span></h2>
      <p><span class="en-text">Book your appointment online with our expert doctors and get the best healthcare services.</span><span class="bn-text" style="display: none;">আমাদের বিশেষজ্ঞ ডাক্তারদের সাথে অনলাইনে আপনার অ্যাপয়েন্টমেন্ট বুক করুন এবং সেরা স্বাস্থ্যসেবা পান।</span></p>
      <button class="appointment-btn"><span class="en-text">Book Appointment Now</span><span class="bn-text" style="display: none;">এখনই অ্যাপয়েন্টমেন্ট বুক করুন</span></button>
    </div>
  </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="testimonials-section">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="section-title"><span class="en-text">Patient Testimonials</span><span class="bn-text" style="display: none;">রোগীর প্রশংসাপত্র</span></h2>
      <p class="section-subtitle"><span class="en-text">Hear what our patients have to say about their experience with us</span><span class="bn-text" style="display: none;">আমাদের সাথে তাদের অভিজ্ঞতা সম্পর্কে আমাদের রোগীরা কি বলেছেন তা শুনুন</span></p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-rating">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
          <p><span class="en-text">Niramoy Health saved my father's life during a heart attack. The ambulance arrived in just 8 minutes!</span><span class="bn-text" style="display: none;">নিরাময় হেলথ হার্ট অ্যাটাকের সময় আমার বাবার জীবন বাঁচিয়েছে। অ্যাম্বুলেন্স মাত্র ৮ মিনিটের মধ্যে এসেছিল!</span></p>
          <div class="client-info">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Client" class="client-img">
            <div>
              <h4 class="client-name"><span class="en-text">Fatima Rahman</span><span class="bn-text" style="display: none;">ফাতেমা রহমান</span></h4>
              <p class="client-title"><span class="en-text">Patient's Daughter</span><span class="bn-text" style="display: none;">রোগীর কন্যা</span></p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-rating">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
          <p><span class="en-text">As a doctor, Niramoy Health has made it easier to manage appointments and access patient histories instantly.</span><span class="bn-text" style="display: none;">একজন ডাক্তার হিসেবে, নিরাময় হেলথ অ্যাপয়েন্টমেন্ট পরিচালনা এবং রোগীর ইতিহাস তাত্ক্ষণিকভাবে অ্যাক্সেস করা সহজ করেছে।</span></p>
          <div class="client-info">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Client" class="client-img">
            <div>
              <h4 class="client-name"><span class="en-text">Dr. Ahmed Khan</span><span class="bn-text" style="display: none;">ডাঃ আহমেদ খান</span></h4>
              <p class="client-title"><span class="en-text">Cardiologist</span><span class="bn-text" style="display: none;">হৃদরোগ বিশেষজ্ঞ</span></p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-rating">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
          <p><span class="en-text">Booking home nursing care for my elderly mother has never been easier. The nurses are professional and caring.</span><span class="bn-text" style="display: none;">আমার বৃদ্ধ মায়ের জন্য হোম নার্সিং কেয়ার বুকিং কখনোই এত সহজ ছিল না। নার্সরা পেশাদার এবং যত্নশীল।</span></p>
          <div class="client-info">
            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Client" class="client-img">
            <div>
              <h4 class="client-name"><span class="en-text">Nusrat Jahan</span><span class="bn-text" style="display: none;">নুসরাত জাহান</span></h4>
              <p class="client-title"><span class="en-text">Caregiver</span><span class="bn-text" style="display: none;">যত্নকারী</span></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>