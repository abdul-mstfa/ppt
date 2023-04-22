<?php
require_once("https://legacygroups.com/billing/includes/api.php");

// Replace these values with your WHMCS API credentials
$api_identifier = 'gJ2Su6jwlC144dYEW3kyuLALjOloriT1';
$api_secret = 'iJmT7zwf9PqqFBVNQ1FN6Fax1d2sPvGZ';

$whmcs = new WHMCS_API($api_identifier, $api_secret);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $postData = array(
        'action' => 'AddAdmin',
        'username' => $email, // Use email as the username
        'password2' => $password,
        'firstname' => $name,
        'lastname' => '',
        'email' => $email,
        'roleid' => 4, // Assign the appropriate role ID for support administrators
    );

    $response = $whmcs->callAPI($postData);

    if ($response['result'] == 'success') {
        echo "Account created successfully!";
    } else {
        echo "Error: " . $response['message'];
    }
}
