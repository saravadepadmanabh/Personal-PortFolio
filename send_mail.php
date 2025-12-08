<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "portfolio";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name    = $_POST['name'];
$email   = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

// Store in database
$stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

if($stmt->execute()){
    // Send Email
    $to      = "padmanabhsaravade@gmail.com";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $mailBody = "Name: $name\nEmail: $email\nSubject: $subject\nMessage:\n$message";

    if (mail($to, $subject, $mailBody, $headers)) {
        echo "Thank you for your message! I will get back to you soon.";
    } else {
        echo "Message stored but failed to send email.";
    }
} else {
    echo "Failed to store your message. Please try again.";
}

$stmt->close();
$conn->close();
?>
