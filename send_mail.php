<?php
// Check if PHPMailer is installed, if not provide instructions
$phpmailer_path = __DIR__ . '/vendor/autoload.php';

if (!file_exists($phpmailer_path)) {
    die('PHPMailer library not found. Please install it using: composer require phpmailer/phpmailer');
}

require $phpmailer_path;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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

try {
    // Create PHPMailer instance
    $mail = new PHPMailer(true);

    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';              // Gmail SMTP server
    $mail->SMTPAuth   = true;                          // Enable SMTP authentication
    $mail->Username   = 'your-email@gmail.com';        // ← REPLACE WITH YOUR GMAIL
    $mail->Password   = 'your-app-password';           // ← REPLACE WITH APP PASSWORD (NOT regular password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;// TLS encryption
    $mail->Port       = 587;                           // Gmail SMTP port
    $mail->SMTPDebug  = 0;                             // Set to 2 for debugging (don't use in production)

    // Email recipients and sender
    $mail->setFrom('your-email@gmail.com', 'Portfolio Contact Form');
    $mail->addAddress('padmanabhsaravade@gmail.com');  // Recipient email
    $mail->addReplyTo($email, $name);                  // Reply-to sender's email

    // Email subject and body
    $mail->isHTML(false);
    $mail->Subject = "New Contact: $subject";

    // Build email body
    $emailBody = "=== New Contact Form Message ===\n\n";
    $emailBody .= "Name: $name\n";
    $emailBody .= "Email: $email\n";
    $emailBody .= "Subject: $subject\n";
    $emailBody .= "Date: " . date('Y-m-d H:i:s') . "\n";
    $emailBody .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $emailBody .= "\n--- Message ---\n";
    $emailBody .= $message . "\n";
    $emailBody .= "\n=== End Message ===\n";

    $mail->Body = $emailBody;

    // Send email
    if ($mail->send()) {
        echo "Thank you for your message! I will get back to you soon.";
        http_response_code(200);
    } else {
        echo "Failed to send email. Please try again later.";
        http_response_code(500);
    }

} catch (Exception $e) {
    // Handle errors
    error_log("Mail Error: " . $mail->ErrorInfo);
    echo "Error sending message: " . $mail->ErrorInfo;
    http_response_code(500);
}

exit();
?>
