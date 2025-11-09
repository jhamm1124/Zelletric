<?php
// ===============================
// Zellectric Contact Form Handler
// ===============================

// Start session and set security headers
session_start();
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

// Set the default timezone to Eastern Time (America/New_York)
date_default_timezone_set('America/New_York');
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

// Check if rate limit has expired or doesn't exist
$currentTime = time();
if (!isset($_SESSION[$rateLimitKey]) || $currentTime >= $_SESSION[$rateLimitKey]['reset_time']) {
    // Reset the counter if the time window has passed
    $_SESSION[$rateLimitKey] = [
        'count' => 1,
        'reset_time' => $currentTime + $rateLimitWindow
    ];
} else {
    // Check if we've hit the rate limit
    if ($_SESSION[$rateLimitKey]['count'] >= $maxRequests) {
        $resetTime = $_SESSION[$rateLimitKey]['reset_time'];
        $timeLeft = ceil(($resetTime - $currentTime) / 60);
        
        http_response_code(429);
        echo json_encode([
            'success' => false, 
            'message' => 'You\'ve reached the maximum number of submissions. Please wait <span class="countdown" data-reset="' . $resetTime . '">' . $timeLeft . '</span> minutes before trying again.',
            'reset_time' => $resetTime
        ]);
        exit;
    }
    
    // Increment the counter if within limits
    $_SESSION[$rateLimitKey]['count']++;
}

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

    // Email Subject
    $subject = 'New Contact Form Submission: ' . 
        ($service ? preg_replace('/[^\w\s-]/', '', $service) : 'General Inquiry');

    // HTML Email Body
    $htmlBody = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; }
            .header { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
            .details { margin: 20px 0; }
            .detail-row { margin-bottom: 10px; }
            .label { font-weight: bold; color: #2c3e50; }
            .message { 
                background-color: #f8f9fa; 
                padding: 15px; 
                border-left: 4px solid #3498db;
                margin: 20px 0;
            }
            .footer { 
                margin-top: 30px; 
                font-size: 0.9em; 
                color: #7f8c8d;
                border-top: 1px solid #eee;
                padding-top: 10px;
            }
        </style>
    </head>
    <body>
        <div class='header'>
            <h2>New Contact Form Submission</h2>
            <p>" . date('F j, Y \a\t g:i a T') . "</p>
        </div>
        
        <div class='details'>
            <div class='detail-row'><span class='label'>Name:</span> " . htmlspecialchars($name) . "</div>
            <div class='detail-row'><span class='label'>Email:</span> <a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a></div>
            <div class='detail-row'><span class='label'>Phone:</span> " . ($phone ? htmlspecialchars($phone) : 'Not provided') . "</div>
            " . ($service ? "<div class='detail-row'><span class='label'>Service:</span> " . htmlspecialchars($service) . "</div>" : "") . "
        </div>
        
        <div class='message'>
            <div class='label'>Message:</div>
            <div>" . nl2br(htmlspecialchars($message)) . "</div>
        </div>
        
        <div class='footer'>
            <p>This message was sent from the contact form on " . $_SERVER['HTTP_HOST'] . "</p>
        </div>
    </body>
    </html>
    ";

    // Plain text version for email clients that don't support HTML
    $textBody = "NEW CONTACT FORM SUBMISSION\n";
    $textBody .= str_repeat("=", 30) . "\n\n";
    $textBody .= "Date: " . date('F j, Y \a\t g:i a T') . "\n";
    $textBody .= "Name: " . $name . "\n";
    $textBody .= "Email: " . $email . "\n";
    $textBody .= "Phone: " . ($phone ?: 'Not provided') . "\n";
    if ($service) {
        $textBody .= "Service: " . $service . "\n";
    }
    $textBody .= "\nMESSAGE:\n" . str_repeat("-", 30) . "\n" . $message . "\n\n";
    $textBody .= "This message was sent from the contact form on " . $_SERVER['HTTP_HOST'] . "\n";

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $htmlBody;
    $mail->AltBody = $textBody;

    // Send the email
    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent.']);
} catch (Exception $e) {
    error_log('Email sending failed: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again later.']);
}
