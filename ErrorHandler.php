<?php
session_start();

// Function to display errors
function displayError($errorMessage) {
    echo '<h2>Error Occurred</h2>';
    echo '<p>' . $errorMessage . '</p>';
    echo '<p><a href="index.php">Back to Home</a></p>'; // Adjust link as per your application
    exit;
}

// Check for specific errors and display messages accordingly
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    
    switch ($error) {
        case 'google_auth':
            displayError('Failed to authenticate with Google. Please try again.');
            break;
        
        case 'otp_generation':
            displayError('Failed to generate OTP. Please try again.');
            break;
        
        default:
            displayError('An unexpected error occurred. Please try again later.');
            break;
    }
} else {
    displayError('An unexpected error occurred. Please try again later.');
}
?>
