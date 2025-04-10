<?php
// Set error reporting for debugging (remove in production)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Email configuration
$recipient_email = "office@resinartfactory.ro";
$email_subject_prefix = "Website Contact Form: ";

// Function to sanitize form data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get and sanitize form data
    $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? sanitize_input($_POST['subject']) : '';
    $message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';
    $captcha_answer = isset($_POST['captcha_answer']) ? (int)$_POST['captcha_answer'] : 0;
    
    // Create response array
    $response = array();
    
    // Check the CAPTCHA (server-side validation)
    if ($captcha_answer !== 8) { // 5 + 3 = 8
        $response['success'] = false;
        $response['message'] = "Incorrect captcha verification.";
        echo json_encode($response);
        exit;
    }
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $response['success'] = false;
        $response['message'] = "All fields are required.";
        echo json_encode($response);
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['success'] = false;
        $response['message'] = "Invalid email format.";
        echo json_encode($response);
        exit;
    }
    
    // Prepare email headers
    $email_subject = $email_subject_prefix . $subject;
    $email_body = "You have received a new message from your website contact form:\n\n";
    $email_body .= "Name: " . $name . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "Subject: " . $subject . "\n\n";
    $email_body .= "Message:\n" . $message . "\n";
    
    // Additional headers
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email
    if (mail($recipient_email, $email_subject, $email_body, $headers)) {
        $response['success'] = true;
        $response['message'] = "Your message has been sent successfully!";
    } else {
        $response['success'] = false;
        $response['message'] = "An error occurred while sending your message. Please try again.";
    }
    
    // Return JSON response
    echo json_encode($response);
    exit;
} else {
    // If accessed directly without POST data
    header("HTTP/1.1 403 Forbidden");
    echo "Direct access to this file is forbidden.";
    exit;
}
?>