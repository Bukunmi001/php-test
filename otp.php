<?php
session_start();

// Function to generate OTP
function generateOTP() {
    $digits = 6; // Length of OTP
    $otp = '';
    for ($i = 0; $i < $digits; $i++) {
        $otp .= random_int(0, 9); // Append random digit (0-9)
    }
    return $otp;
}

// Handle Google authentication and retrieve user info 
// Example: Get user's email from Google login
$userEmail = ""; // Replace with actual logic to get user's email

// Generate OTP
$otp = generateOTP();

// Store OTP in session (valid for 30 seconds)
$_SESSION['otp'] = [
    'code' => $otp,
    'expiry_time' => time() + 30, // Expiry time 30 seconds from now
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>OTP Page</title>
</head>
<body>
    <h2>One-Time Password (OTP)</h2>
    <div>
        <?php
        // Retrieve OTP from session if available
        if (isset($_SESSION['otp'])) {
            $otp = $_SESSION['otp']['code'];
            $expiryTime = $_SESSION['otp']['expiry_time'];

            // Check if OTP is still valid
            if (time() <= $expiryTime) {
                // Display OTP on the UI
                echo "<p>Your OTP: $otp</p>";
            } else {
                // OTP has expired, handle accordingly (e.g., display error message)
                echo "<p>OTP has expired.</p>";
            }
        } else {
            // No OTP found in session, handle accordingly (e.g., display error message)
            echo "<p>OTP not available.</p>";
        }
        ?>
    </div>

    <!-- Logout Link -->
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
