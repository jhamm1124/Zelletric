<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitemap - Zellectric</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .sitemap-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .sitemap-container h1 {
            color: #1a365d;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .sitemap-section {
            margin-bottom: 2rem;
        }
        .sitemap-section h2 {
            color: #2c5282;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        .sitemap-links {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
        .sitemap-links a, .service-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #4a5568;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s ease;
            cursor: default;
        }
        .sitemap-links a:hover {
            background-color: #f7fafc;
            color: #2b6cb0;
            transform: translateX(5px);
        }
        
        .service-item {
            pointer-events: none;
        }
        .sitemap-links i {
            margin-right: 0.75rem;
            color: #4299e1;
            width: 20px;
            text-align: center;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            margin-bottom: 2rem;
            color: #2b6cb0;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link i {
            margin-right: 0.5rem;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="header-container">
            <div class="logo-wrapper">
                <img src="images/Logo.png" alt="Zellectric Electrical Services" class="header-logo">
            </div>
            <a href="index.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="sitemap-container">
        <h1>Website Sitemap</h1>
        
        <div class="sitemap-section">
            <h2>Main Pages</h2>
            <div class="sitemap-links">
                <a href="index.php">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="index.php#services">
                    <i class="fas fa-bolt"></i> Services
                </a>
                <a href="index.php#about">
                    <i class="fas fa-info-circle"></i> About Us
                </a>
                <a href="index.php#contact">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </div>
        </div>
        
        <div class="sitemap-section">
            <h2>Our Services</h2>
            <div class="sitemap-links">
                <div class="service-item">
                    <i class="fas fa-tools"></i> Electrical Repairs
                </div>
                <div class="service-item">
                    <i class="fas fa-wrench"></i> Residential Wiring
                </div>
                <div class="service-item">
                    <i class="fas fa-lightbulb"></i> Lighting & Fan Installation
                </div>
                <div class="service-item">
                    <i class="fas fa-charging-station"></i> EV Charging Solutions
                </div>
            </div>
        </div>
        
        <div class="sitemap-section">
            <h2>Legal & Information</h2>
            <div class="sitemap-links">
                <a href="privacy-policy.php">
                    <i class="fas fa-shield-alt"></i> Privacy Policy
                </a>
                <a href="terms-of-service.php">
                    <i class="fas fa-file-contract"></i> Terms of Service
                </a>
                <a href="sitemap.php">
                    <i class="fas fa-sitemap"></i> Sitemap
                </a>
            </div>
        </div>
        
        <div class="sitemap-section">
            <h2>Contact Information</h2>
            <div class="sitemap-links">
                <a href="tel:7174620764">
                    <i class="fas fa-phone"></i> (717) 462-0764
                </a>
                <a href="mailto:contact@zellectric.com">
                    <i class="fas fa-envelope"></i> contact@zellectric.com
                </a>
                <a href="#" onclick="alert('Our business hours are Monday-Friday: 3:00 PM - 8:00 PM')">
                    <i class="far fa-clock"></i> Business Hours
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-bottom">
            <div class="footer-container">
                <p>&copy; 2025 Zellectric. All Rights Reserved.</p>
                <div class="footer-legal">
                    <a href="privacy-policy.php">Privacy Policy</a>
                    <span>|</span>
                    <a href="terms-of-service.php">Terms of Service</a>
                    <span>|</span>
                    <a href="sitemap.php">Sitemap</a>
                    <span>|</span>
                    <a href="index.php">Home</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
