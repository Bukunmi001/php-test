<?php
session_start();
require_once 'vendor/autoload.php'; // Include Composer's autoload file

use Google\Client;
use Google\Service\Oauth2;

// Load config
require_once 'config.php';

// Create Google Client
$client = new Client();
$client->setClientId($config['client_id']);
$client->setClientSecret($config['client_secret']);
$client->setRedirectUri($config['redirect_uri']);
$client->addScope($config['scopes']);

// Handle OAuth callback
if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $_SESSION['access_token'] = $token;

        // Get user info
        $oauth2 = new Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        // Generate OTP (Example: 6-digit random number)
        function generateOTP() {
            $digits = 6; // Length of OTP
            $otp = '';
            for ($i = 0; $i < $digits; $i++) {
                $otp .= random_int(0, 9); // Append random digit (0-9)
            }
            return $otp;
        }

        // Function to check if OTP is expired
        function isOTPExpired() {
            if (isset($_SESSION['otp']['expiry_time']) && $_SESSION['otp']['expiry_time'] >= time()) {
                return false;
            }
            return true;
        }

        // Generate OTP if not already generated or expired
        if (!isset($_SESSION['otp']) || isOTPExpired()) {
            $otp = generateOTP();
            $_SESSION['otp'] = [
                'code' => $otp,
                'expiry_time' => time() + 30, // Expiry time 30 seconds from now
            ];
        } else {
            $otp = $_SESSION['otp']['code'];
        }

        // Display user info and OTP
        echo '<h2>Logged in successfully with Google!</h2>';
        echo '<p>Email: ' . $userInfo->getEmail() . '</p>';
        echo '<p>Name: ' . $userInfo->getName() . '</p>';

        if (!isOTPExpired()) {
            echo '<p id="otp">Your OTP: ' . $otp . '</p>';
        } else {
            $_SESSION['error'] = 'otp_generation';
            header('Location: ErrorHandler.php');
            exit;
        }

        // Logout link
        echo '<p><a href="logout.php">Logout</a></p>';

        // Clear session token on logout
        unset($_SESSION['access_token']);
    } catch (Exception $e) {
        $_SESSION['error'] = 'google_auth';
        header('Location: ErrorHandler.php');
        exit;
    }
} else {
    $_SESSION['error'] = 'google_auth';
    header('Location: ErrorHandler.php');
    exit;
}
?>


<script>
// JavaScript to hide OTP after 30 seconds
setTimeout(function() {
    var otpElement = document.getElementById('otp');
    if (otpElement) {
        otpElement.style.display = 'none';
    }
}, 30000); // 30 seconds in milliseconds
</script>
