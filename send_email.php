<?php
// Get form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$service = $_POST['service'] ?? '';
$message = $_POST['message'] ?? '';

// Validate required fields
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

// Set recipient email (replace with your email on Namecheap)
$to = 'contact@zellectric.com';
$subject = "New Contact Form Submission: $service";

// Build email content
$email_content = "New Contact Form Submission\n\n";
$email_content .= "Name: $name\n";
$email_content .= "Email: $email\n";
$email_content .= "Phone: " . ($phone ?: 'Not provided') . "\n";
$email_content .= "Service: $service\n\n";
$email_content .= "Message:\n$message\n";

// Set headers
$headers = [
    'From' => "$name <$email>",
    'Reply-To' => $email,
    'X-Mailer' => 'PHP/' . phpversion()
];

// Convert headers to string
$headers_string = '';
foreach ($headers as $key => $value) {
    $headers_string .= "$key: $value\r\n";
}

// Send email
if (mail($to, $subject, $email_content, $headers_string)) {
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again later.']);
}
