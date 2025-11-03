<?php
// Start session and set security headers
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Rate limiting
$rateLimitWindow = 900; // 15 minutes in seconds
$maxRequests = 5; // Max requests per window
$ip = $_SERVER['REMOTE_ADDR'];
$rateLimitKey = 'rate_limit_' . md5($ip);

// Initialize rate limit if not set
if (!isset($_SESSION[$rateLimitKey])) {
    $_SESSION[$rateLimitKey] = [
        'count' => 0,
        'reset_time' => time() + $rateLimitWindow
    ];
}

// Check rate limit
if ($_SESSION[$rateLimitKey]['count'] >= $maxRequests) {
    if (time() < $_SESSION[$rateLimitKey]['reset_time']) {
        http_response_code(429); // Too Many Requests
        echo json_encode([
            'success' => false, 
            'message' => 'Too many requests. Please try again later.'
        ]);
        exit;
    } else {
        // Reset the counter if the window has passed
        $_SESSION[$rateLimitKey] = [
            'count' => 0,
            'reset_time' => time() + $rateLimitWindow
        ];
    }
}

// Increment request count
$_SESSION[$rateLimitKey]['count']++;

// CSRF Protection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        http_response_code(403);
        echo json_encode([
            'success' => false, 
            'message' => 'Invalid request. Please refresh the page and try again.'
        ]);
        exit;
    }
}

// Generate new CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return $data;
}

// Get and sanitize form data
$name = sanitizeInput($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$phone = sanitizeInput($_POST['phone'] ?? '');
$service = sanitizeInput($_POST['service'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');

// Validate required fields
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Please fill in all required fields.'
    ]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Please enter a valid email address.'
    ]);
    exit;
}

// Validate name length
if (strlen($name) > 100) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Name is too long. Maximum 100 characters allowed.'
    ]);
    exit;
}

// Validate message length
if (strlen($message) > 2000) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Message is too long. Maximum 2000 characters allowed.'
    ]);
    exit;
}

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoloader
require 'vendor/autoload.php';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'contact@zellectric.com';
    $mail->Password = 'Zellectric2024!';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Recipients
    $mail->setFrom('contact@zellectric.com', 'Zellectric Contact Form');
    $mail->addAddress('contact@zellectric.com'); // send to same inbox
    $mail->addReplyTo($email, $name); // so replying goes to the visitor's email

    // Sanitize service for subject
    $subject = 'New Contact Form Submission: ' . 
        ($service ? preg_replace('/[^\w\s-]/', '', $service) : 'General Inquiry');

    // Build email content with proper escaping
    $email_content = "New Contact Form Submission\n\n";
    $email_content .= "Name: " . str_replace("\r\n", "\n", $name) . "\n";
    $email_content .= "Email: " . $email . "\n";
    $email_content .= "Phone: " . ($phone ? str_replace("\r\n", "\n", $phone) : 'Not provided') . "\n";
    $email_content .= "Service: " . str_replace("\r\n", "\n", $service) . "\n\n";
    $email_content .= "Message:\n" . str_replace("\r\n", "\n", $message) . "\n";

    // Content
    $mail->isHTML(false); // Set email format to plain text
    $mail->Subject = $subject;
    $mail->Body = $email_content;

    // Send email
    $mail->send();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Thank you! Your message has been sent.'
    ]);
} catch (Exception $e) {
    error_log('Email sending failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to send message. Please try again later.'
    ]);
}
