<?php
// ===============================
// Zellectric Contact Form Handler
// ===============================

// Start session and set security headers
session_start();
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// -------------------------------
// Rate Limiting (2 per 10 minutes)
// -------------------------------
$rateLimitWindow = 600; // 10 minutes in seconds
$maxRequests = 2;
$ip = $_SERVER['REMOTE_ADDR'];
$rateLimitKey = 'rate_limit_' . md5($ip);

if (!isset($_SESSION[$rateLimitKey])) {
    $_SESSION[$rateLimitKey] = [
        'count' => 0,
        'reset_time' => time() + $rateLimitWindow
    ];
}

if ($_SESSION[$rateLimitKey]['count'] >= $maxRequests) {
    if (time() < $_SESSION[$rateLimitKey]['reset_time']) {
        $timeLeft = ceil(($_SESSION[$rateLimitKey]['reset_time'] - time()) / 60);
        http_response_code(429);
        echo json_encode([
            'success' => false, 
            'message' => "You've reached the maximum number of submissions. Please wait {$timeLeft} minutes before trying again."
        ]);
        exit;
    } else {
        $_SESSION[$rateLimitKey] = ['count' => 0, 'reset_time' => time() + $rateLimitWindow];
    }
}
$_SESSION[$rateLimitKey]['count']++;

// -------------------------------
// CSRF Token Check (optional)
// -------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Invalid request. Please refresh the page and try again.']);
        exit;
    }
}

// Generate new CSRF token if not present
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// -------------------------------
// Sanitize User Input
// -------------------------------
function sanitizeInput($data) {
    return htmlspecialchars(trim(stripslashes($data)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

$name = sanitizeInput($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$phone = sanitizeInput($_POST['phone'] ?? '');
$service = sanitizeInput($_POST['service'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');

// -------------------------------
// Validation
// -------------------------------
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

if (strlen($name) > 100 || strlen($message) > 2000) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Input too long.']);
    exit;
}

// -------------------------------
// PHPMailer Setup
// -------------------------------
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php'; // Safely load PHPMailer
$config = require __DIR__ . '/config.php';

$email_user = $config['email_user'];
$email_pass = $config['email_pass'];

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'mail.zellectric.com';
    $mail->SMTPAuth = true;
    $mail->Username = $email_user;
    $mail->Password = $email_pass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];



    $mail->setFrom($email_user, 'Zellectric Contact Form');
    $mail->addAddress($email_user);
    $mail->addReplyTo($email, $name);

    // Email Subject & Body
    $subject = 'New Contact Form Submission: ' . 
        ($service ? preg_replace('/[^\w\s-]/', '', $service) : 'General Inquiry');

    $body = "New Contact Form Submission\n\n";
    $body .= "Name: {$name}\n";
    $body .= "Email: {$email}\n";
    $body .= "Phone: " . ($phone ?: 'Not provided') . "\n";
    $body .= "Service: " . ($service ?: 'Not specified') . "\n\n";
    $body .= "Message:\n{$message}\n";

    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $body;

    // Send the email
    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent.']);
} catch (Exception $e) {
    error_log('Email sending failed: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again later.']);
}
