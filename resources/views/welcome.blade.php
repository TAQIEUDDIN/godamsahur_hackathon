<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>MusafirBuddy</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Arsha
  * Template URL: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
  * Updated: Feb 22 2025 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

  <style>
  /* Add this to your existing styles */
  .navmenu ul li a.auth-link {
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    transition: all 0.3s ease;
  }

  .navmenu ul li a.login-link {
    background-color: transparent;
    border: 1px solid white;
  }

  .navmenu ul li a.register-link {
    background-color: var(--primary-color);
    border: 1px solid var(--primary-color);
  }

  .navmenu ul li a.auth-link:hover {
    background-color: white;
    color: var(--primary-color);
  }

  /* Style for user dropdown */
  .navmenu .user-dropdown {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
  }

  .navmenu .user-dropdown:hover {
    background: rgba(255, 255, 255, 0.2);
  }
  </style>
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">MusafirBuddy</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="javascript:void(0)" onclick="scrollToSection('hero')" >Home</a></li>
          <li> <a href="{{url('/Place')}}">Find Location</a></li>
          <li class="dropdown"><a href="#"><span>Prayer</span><i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="{{url('/prayer-times')}}">Prayer Times</a></li>
              <li><a href="javascript:void(0)" onclick="scrollToSection('call-to-action')">Prayer Guide</a></li>
            </ul>
          </li>

          <li class="dropdown"><a href="#"><span>Discover Us</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="javascript:void(0)" onclick="scrollToSection('about')">About Us</a></li>
              <li><a href="javascript:void(0)" onclick="scrollToSection('services')">Features</a></li>
              <li><a href="javascript:void(0)" onclick="scrollToSection('team')">Team</a></li>
            </ul>
          </li>
          @auth
            <li><a href="{{ route('reviews.index') }}">Reviews</a></li>
        @endauth
          <!-- Add these authentication links -->
          @guest
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
          @else
            <li class="dropdown">
              <a href="#"><span>{{ Auth::user()->name }}</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
              <ul>
                <li><a href="{{ route('profile.show') }}">Profile</a></li>
                <li>
                  <a href="{{ route('logout') }}"
                     onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
                    Logout
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                  </form>
                </li>
              </ul>
            </li>
          @endguest
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <!-- Add this right after your header section -->
  @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert" 
           style="position: fixed; top: 80px; right: 20px; z-index: 1000;">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
  @endif

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
            <h1>Your Ultimate Travel Companion </h1>
            <p>Easy, Fast, and At Your Fingertips.</p>
            <div class="d-flex">
              <a href="{{url('/Place')}}" class="btn-get-started"><i class="bi bi-car-front"></i> Start Your Journey</a>
              <a href="javascript:void(0)" onclick="scrollToSection('guidance')" class="btn-how-it-works d-flex align-items-center">
               <span>How it works <i class="bi bi-question-circle"></i></span>
              </a>
            </div>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="200">
            <img src="assets/img/person/malaysia.png" class="img-fluid animated" alt="">
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

   <!-- About Section -->
<section id="about" class="about section">

<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
  <h2>About MusafirBuddy</h2>
</div><!-- End Section Title -->

<div class="container">
  <div class="row gy-4">

    <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
      <p>
        MuslimBuddy is your ultimate travel companion, designed to make exploring Malaysia easier and more convenient. 
        Whether you're looking for restaurants, nearby mosques, or a comfortable stay, our app provides real-time 
        recommendations tailored to your needs.
      </p>
      <ul>
        <li><i class="bi bi-check2-circle"></i> <span>Find restaurants, hotels, and mosques near you.</span></li>
        <li><i class="bi bi-check2-circle"></i> <span>Get real-time prayer times.</span></li>
        <li><i class="bi bi-check2-circle"></i> <span>Connect with fellow travelers and share experiences.</span></li>
      </ul>
    </div>

    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
      <p>
        Powered by modern technology, MuslimBuddy integrates smart APIs like Mapbox and Foursquare to deliver accurate 
        and updated travel insights. Whether you're on a business trip, vacation, or just exploring, we've got you covered.
      </p>
    </div>

  </div>
</div>

</section><!-- /About Section -->

    <!-- Why Us Section -->
    <section id="guidance" class="section why-us light-background" data-builder="section">
      <div class="container-fluid">
        <div class="row gy-4">
          <div class="col-lg-7 d-flex flex-column justify-content-center order-2 order-lg-1">
            <div class="content px-xl-5" data-aos="fade-up" data-aos-delay="100">
              <h3>How to Use Our Search Features</h3>
              <div class="faq-container px-xl-5" data-aos="fade-up" data-aos-delay="200">
                <!-- Step 1 -->
                <div class="step-card">
                  <div class="step-header">
                    <span class="step-number">1.</span>
                    <span class="step-title">Enable Location Services</span>
                  </div>
                  <div class="step-details">
                    <p>Allow location access when prompted to help us find prayer spaces near you. This ensures accurate distance calculations and better search results.</p>
                  </div>
                </div>

                <!-- Step 2 -->
                <div class="step-card">
                  <div class="step-header">
                    <span class="step-number">2.</span>
                    <span class="step-title">Search by Area</span>
                  </div>
                  <div class="step-details">
                    <p>Use general location terms for better results:</p>
                    <ul>
                      <li>✓ Good searches: "Ayer Keroh", "Melaka", "Bandar Hilir"</li>
                      <li>✗ Avoid specific: "Kedai Makan ABC", "Restaurant XYZ"</li>
                    </ul>
                  </div>
                </div>

                <!-- Step 3 -->
                <div class="step-card">
                  <div class="step-header">
                    <span class="step-number">3.</span>
                    <span class="step-title">Understanding Results</span>
                  </div>
                  <div class="step-details">
                    <p>For each location, we provide:</p>
                    <ul>
                      <li>• Distance from your current location</li>
                      <li>• Whether prayers can be combined (Jama' and Qasr)</li>
                      <li>• Directions to the location (Google Maps & Waze)</li>
                    </ul>
                  </div>
                </div>

                <!-- Step 4 -->
                <div class="step-card">
                  <div class="step-header">
                    <span class="step-number">4.</span>
                    <span class="step-title">Filter Options</span>
                  </div>
                  <div class="step-details">
                    <p>Refine your search using categories:</p>
                    <ul>
                    <li>• Mosques</li>
                    <li>• Restaurants</li>
                    <li>• Hotels</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-5 order-1 order-lg-2 why-us-img">
            <div class="map-container" data-aos="zoom-in" data-aos-delay="100">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d254779.42589834897!2d101.58795367778618!3d3.139020117784827!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc49c701efeae7%3A0xf4d98e5b2f1c287d!2sKuala%20Lumpur%2C%20Federal%20Territory%20of%20Kuala%20Lumpur!5e0!3m2!1sen!2smy!4v1709799171599!5m2!1sen!2smy"
                style="border:0; width: 100%; height: 500px; border-radius: 8px; box-shadow: 0 0 20px rgba(0,0,0,0.1);"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
              </iframe>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- Services Section -->
    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Features</h2>
        <p>Enhancing your travel experience with essential features tailored for Muslim travelers.</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up"  data-aos-delay="100">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-geo-alt-fill"></i></div>
              <h4><a href="{{url('/Place')}}" class="stretched-link">Find Locations</a></h4>
              <p>Search for nearby halal restaurants, hotels, and mosques with ease.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-clock"></i></div>
              <h4><a href="{{url('/prayer-times')}}" class="stretched-link">Prayer Essentials</a></h4>
              <p>Get accurate prayer times and Qibla direction based on your location.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-chat-left-text"></i></div>
              <h4><a href="{{url('/reviews')}}" class="stretched-link">Community Reviews</a></h4>
              <p>Read and share experiences with fellow travelers about Muslim-friendly places.<strong>User need to login first to access this feature</strong></p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-moon-stars"></i></div>
              <h4><a href="javascript:void(0)" onclick="scrollToSection('call-to-action')" class="stretched-link">Jama' and Qasr Guidance</a></h4>
              <p>Follow step-by-step instructions on how to perform Jama' (combined) and Qasr (shortened) prayers while traveling, based on guidelines provided by <strong>JAKIM</strong> to ensure validity and ease.</p>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Services Section -->

    <section id="call-to-action" class="call-to-action section dark-background">
    <img src="assets/img/bg/sendayan.jpg" alt="gambar masjid" class="bg-image">
    
    <div class="container position-relative" style="z-index: 2;">
        <div class="row" data-aos="zoom-in" data-aos-delay="100">
            <div class="col-xl-9 text-center text-xl-start">
                <h3>Panduan Solat Jamak & Qasar</h3>
                <p>Fahami panduan menunaikan solat jamak dan qasar mengikut garis panduan JAKIM. Permudahkan perjalanan anda sambil mengekalkan ibadah.</p>
            </div>
            <div class="col-xl-3 cta-btn-container text-center">
                <a class="cta-btn align-middle" href="{{url('https://ecentral.my/solat-jamak-dan-qasar/')}}" target="_blank">
                    <i class="bi bi-book-half"></i> Panduan Lengkap
                </a>
            </div>
        </div>

        <div class="faq-section mt-5" data-aos="fade-up" data-aos-delay="200">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="faq-container">
                        <div class="faq-item glass-effect">
                            <h3><span>01</span> Bila Boleh Jamak & Qasar?</h3>
                            <div class="faq-content">
                                <p><i class="faq-toggle bi bi-chevron-right"></i>Apabila bermusafir melebihi 81km (2 marhalah) dengan tujuan yang jelas dan dibenarkan syarak.</p>
                            </div>
                            
                        </div>

                        <div class="faq-item glass-effect">
                            <h3><span>02</span> Berapa Lama Tempoh Dibenarkan?</h3>
                            <div class="faq-content">
                                <p><i class="faq-toggle bi bi-chevron-right"></i>Sehingga 3 hari 3 malam di destinasi, tidak termasuk hari sampai dan hari balik.</p>
                            </div>
                            
                        </div>

                        <div class="faq-item glass-effect">
                            <h3><span>03</span> Solat Yang Boleh Dijamak & Qasar?</h3>
                            <div class="faq-content"> 
                                <p><i class="faq-toggle bi bi-chevron-right"></i>Zohor dengan Asar, Maghrib dengan Isyak. Untuk Qasar, Zohor, Asar dan Isyak dari 4 ke 2 rakaat.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    </section><!-- /Call To Action Section -->

   

       <!-- Team Section -->
       <section id="team" class="team section">
     <!-- Section Title -->
     <div class="container section-title" data-aos="fade-up">
      <h2>Our Team</h2>
      <p>Meet the passionate minds behind MuslimBuddy, dedicated to enhancing Muslim travel experiences.</p>
    </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="team-member d-flex align-items-start">
              <div class="pic"><img src="assets/img/person/taqie.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Taqieuddin</h4>
                <span>Project Leader</span>
                <p>Experienced in Laravel full-stack web development, API integrations, and website aesthetics, dedicated to providing a seamless digital experience for Muslim travelers.</p>
                <div class="social">
                  <a href="{{url('https://www.linkedin.com/in/muhammad-taqieuddin-6312b1293?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app')}}"> <i class="bi bi-linkedin"></i> </a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="team-member d-flex align-items-start">
              <div class="pic"><img src="assets/img/person/adam.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Adam</h4>
                <span>Backend Developer</span>
                <p>Specialized in Database Management, authentication and authorization.</p>
                <div class="social">
                  <a href="{{url('https://www.linkedin.com/in/adam-ikhwan-4a3a95291?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app')}}"> <i class="bi bi-linkedin"></i> </a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-6 mx-auto" data-aos="fade-up" data-aos-delay="300">
            <div class="team-member d-flex align-items-start">
              <div class="pic"><img src="assets/img/person/mal.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Akmal</h4>
                <span>Frontend Developer</span>
                <p>Crafting intuitive and user-friendly designs to help Muslim travelers navigate easily.</p>
                <div class="social">
                  <a href="{{url('https://www.linkedin.com/in/akmal-tajudin-6037b3254?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app')}}"> <i class="bi bi-linkedin"></i> </a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

        </div>

      </div>

    </section><!-- /Team Section -->

  </main>

  <footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="d-flex align-items-center">
            <span class="sitename">MusafirBuddy</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Muzaffar Heights,</p>
            <p>Ayer Keroh, Melaka</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+60 11-21219683</span></p>
            <p><strong>Email:</strong> <span>mtaqieuddin03@gmail.com</span></p>
          </div>
        </div>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/vendor/php-email-form/validate.js"></script>
  <script src="/assets/vendor/aos/aos.js"></script>
  <script src="/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="/assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="/assets/js/main.js"></script>

  <script>
  function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
    }
  }

  // Clear hash on page load
  if (window.location.hash) {
    window.history.replaceState("", document.title, window.location.pathname);
  }

  </script>

  <!-- Add this before closing </body> tag in welcome.blade.php -->
  <script>
    // Auto dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
  </script>

</body>

</html>