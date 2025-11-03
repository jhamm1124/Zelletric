<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Professional electrical services for residential and commercial properties. 24/7 emergency service available.">
    <title>Zellectric | Professional Electrical Services</title>
    <?php
    // Start session and generate CSRF token if it doesn't exist
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ?>
    <meta name="csrf-token" content="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css?v=1.1">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/Lightbulb.png">
    <link rel="apple-touch-icon" href="images/Lightbulb.png">
    <link rel="shortcut icon" href="images/Lightbulb.png" type="image/x-icon">
</head>
<body>
    <!-- Header with Navigation -->
    <header class="site-header">
        <div class="header-container">
            <div class="logo-wrapper">
                <a href="index.php" class="logo-link">
                    <img src="images/Logo.png" alt="Zellectric Electrical Services" class="header-logo">
                </a>
            </div>
            <nav class="main-nav">
                <ul class="nav-list">
                    <li><a href="#home" class="nav-link">Home</a></li>
                    <li><a href="#services" class="nav-link">Services</a></li>
                    <li><a href="#about" class="nav-link">About Us</a></li>
                    <li><a href="#contact" class="nav-link">Contact Us</a></li>
                </ul>
            </nav>
            <div class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1>Professional Electrical Services You Can Trust</h1>
                <p>6 Days a Week • New/Remodeling/Repairs • Free Estimates</p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services">
        <div class="section-header">
            <h2 class="services-title">Services</h2>
            <p class="section-description">Trusted electrical solutions for homes and businesses since 2025</p>
        </div>
        
        <div class="services-grid">
            <div class="service-card">
                <i class="fas fa-bolt"></i>
                <h3>Electrical Repairs & Maintenance</h3>
                <p>Expert troubleshooting and repair services for all your electrical needs. We fix everything from flickering lights to circuit breakers, ensuring your home's electrical system is safe and efficient. Includes comprehensive maintenance to prevent future issues.</p>
            </div>
            <div class="service-card">
                <i class="fas fa-home"></i>
                <h3>Residential Electric</h3>
                <p>Professional wiring and installation services for both new constructions and renovations. Our certified electricians handle everything from basic wiring to complete electrical system installations with precision, including lighting, ceiling fans, and smart home integration.</p>
            </div>
            <div class="service-card">
                <i class="fas fa-charging-station"></i>
                <h3>EV & Energy Solutions</h3>
                <p>Future-proof your home with our EV charging station installations and energy-efficient solutions. We'll help you select and install the perfect charging solution for your electric vehicle while optimizing your home's energy usage for maximum efficiency and savings.</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="section-header">
                <h2>Powering Your World with Excellence</h2>
                <div class="divider"></div>
                <p class="section-description">Trusted electrical solutions for homes and businesses since 2025</p>
            </div>
            
            <div class="about-content">
                <div class="about-text">
                    <div class="value-cards">
                        <div class="value-card">
                            <div class="card-icon">
                                <i class="fas fa-medal"></i>
                            </div>
                            <h3>5+ Years Experience</h3>
                            <p>Trusted expertise in residential and commercial electrical services with a proven track record of excellence.</p>
                        </div>
                        <div class="value-card">
                            <div class="card-icon">
                                <i class="fas fa-church"></i>
                            </div>
                            <h3>Faith-Based Values</h3>
                            <p>Operating with integrity, honesty, and respect - treating every customer like family.</p>
                        </div>
                        <div class="value-card">
                            <div class="card-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <h3>Customer-First</h3>
                            <p>Your satisfaction is our top priority. We listen, understand, and deliver expectations.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
    <div class="contact-container">
        <div class="contact-form">
            <h2>Contact Us</h2>
            <form id="contactForm" method="POST" action="send_email.php" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="service">Service Needed</label>
                        <select id="service" name="service" required>
                            <option value="">Select a Service</option>
                            <option value="repairs">Electrical Repairs</option>
                            <option value="residential">Residential Services</option>
                            <option value="lighting">Lighting Installation</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="message">How can we help you?</label>
                    <textarea id="message" name="message" rows="4" required></textarea>
                </div>

                <button type="submit" class="cta-button">Send Message</button>
            </form>
        </div>
    </div>
    </section>


    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-content">
            
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            
            <!-- <div class="footer-section">
                <h4>Our Services</h4>
                <ul class="footer-links">
                    <li><a href="#" class="no-hover">Electrical Repairs</a></li>
                    <li><a href="#" class="no-hover">Residential Wiring</a></li>
                    <li><a href="#" class="no-hover">Lighting Installation</a></li>
                </ul>
            </div> -->
            
            <div class="footer-section">
                <h4>Contact Info</h4>
                <ul class="contact-info">
                    <li><i class="fas fa-map-marker-alt"></i> Dillsburg, PA 17019</li>
                    <li><i class="fas fa-phone"></i> <a href="tel:+7174620764">(717) 462-0764</a></li>
                    <li><i class="fas fa-envelope"></i> <a href="mailto:contact@zellectric.com">contact@zellectric.com</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Zellectric</h4>
                <p class="footer-about">Your trusted partner for all electrical needs. Providing professional, reliable, and affordable electrical services since 2025.</p>
                <!-- <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div> -->
            </div>

        </div>
        
        <div class="footer-bottom">
            <div class="footer-container">
                <p>&copy; 2025 Zellectric. All Rights Reserved.</p>
                <div class="footer-legal">
                    <a href="privacy-policy.php">Privacy Policy</a>
                    <span>|</span>
                    <a href="terms-of-service.php">Terms of Service</a>
                    <span>|</span>
                    <a href="sitemap.php">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" id="back-to-top" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </a>
    <script>
    // Mobile menu functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu elements
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mainNav = document.querySelector('.main-nav');
        const navLinks = document.querySelectorAll('.nav-link');
        const menuIcon = mobileMenuToggle.querySelector('i');
        let isMenuOpen = false;
        
        // Toggle menu function
        function toggleMenu() {
            isMenuOpen = !isMenuOpen;
            
            // Toggle menu visibility
            if (isMenuOpen) {
                mainNav.classList.add('active');
                document.body.style.overflow = 'hidden';
                menuIcon.className = 'fas fa-times';
            } else {
                mainNav.classList.remove('active');
                document.body.style.overflow = 'auto';
                menuIcon.className = 'fas fa-bars';
            }
        }
        
        // Toggle menu when clicking the hamburger icon
        mobileMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleMenu();
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (isMenuOpen && !mainNav.contains(e.target) && e.target !== mobileMenuToggle) {
                toggleMenu();
            }
        });
        
        // Close menu when clicking on a nav link
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    toggleMenu();
                }
            });
        });
        
        // Toggle menu when clicking the hamburger icon
        if (mobileMenuToggle) {
            mobileMenuToggle.setAttribute('aria-expanded', 'false');
            mobileMenuToggle.addEventListener('click', toggleMenu);
        }
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 && 
                !e.target.closest('.main-nav') && 
                !e.target.closest('.mobile-menu-toggle')) {
                navList.classList.remove('active');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = 'auto';
                const icon = mobileMenuToggle.querySelector('i');
                if (icon) {
                    icon.className = 'fas fa-bars';
                }
            }
        });

        // Header scroll effect
        const header = document.querySelector('.site-header');
        
        // Add initial styles
        header.style.transition = 'all 0.3s ease';
        header.style.background = 'rgba(255, 255, 255, 1)'; // Initial state: solid white
        
        function updateHeader() {
            if (window.scrollY > 50) {
                header.style.background = 'rgba(255, 255, 255, 0.7)';
                header.style.backdropFilter = 'blur(10px)';
                header.style.webkitBackdropFilter = 'blur(10px)';
                header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.15)';
                header.style.padding = '0.7rem 0';
                header.style.borderBottom = '2px solid #f59e0b';
            } else {
                header.style.background = 'rgba(255, 255, 255, 1)';
                header.style.backdropFilter = 'none';
                header.style.webkitBackdropFilter = 'none';
                header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
                header.style.padding = '1.2rem 0';
                header.style.borderBottom = 'none';
            }
        }
        
        // Initial check
        updateHeader();
        
        // Listen for scroll events
        window.addEventListener('scroll', updateHeader);
        
        // Smooth scroll for navigation links
        function setupNavigationLink(selector) {
            document.querySelectorAll(selector).forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        const headerOffset = 50; // Increased from 100 to 120 to land slightly below section top
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        }

        // Set up all navigation links
        setupNavigationLink('a[href^="#"]');
    });

        
    </script>
</body>
</html>