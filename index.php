<?php
// index.php

session_start();

require_once 'vendor/autoload.php'; // Include Composer's autoload file
require_once 'config.php'; // Include your configuration file

use Google\Client;
use Google\Service\Oauth2;

// Create Google Client
$client = new Client();
$client->setClientId($config['client_id']);
$client->setClientSecret($config['client_secret']);
$client->setRedirectUri($config['redirect_uri']);
$client->addScope($config['scopes']);

// Generate Google OAuth URL
$authUrl = $client->createAuthUrl();

// Display Sign-in link
echo '<h2>Sign In with Google</h2>';
echo '<a href="' . $authUrl . '">Sign In with Google</a>';
?>
