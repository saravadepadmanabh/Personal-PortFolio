<?php
header('Content-Type: text/plain; charset=utf-8');

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

// Get form data
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate all fields are filled
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    die('All fields are required');
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Invalid email address');
}

// Sanitize inputs to prevent injection
$name    = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$email   = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

// Email configuration
$to      = "padmanabhsaravade@gmail.com";
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Build email body
$mailBody = "=== New Contact Form Message ===\n\n";
$mailBody .= "Name: $name\n";
$mailBody .= "Email: $email\n";
$mailBody .= "Subject: $subject\n";
$mailBody .= "Date: " . date('Y-m-d H:i:s') . "\n";
$mailBody .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
$mailBody .= "\n--- Message ---\n";
$mailBody .= $message . "\n";
$mailBody .= "\n=== End Message ===\n";

// Attempt to send email
$mail_sent = @mail($to, $subject, $mailBody, $headers);

if ($mail_sent) {
    // Email sent successfully
    echo "Thank you for your message! I will get back to you soon.";
    http_response_code(200);
} else {
    // Mail function failed (server might not be configured)
    echo "Message received but email delivery failed. This could be a server configuration issue. Please try contacting me directly at padmanabhsaravade@gmail.com or call +91 9110843494";
    http_response_code(500);
}

exit();
?>
